<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::latest()->get();
        $totalDividas = 0;
        return view('Clientes.index', ['clientes' => $clientes, 'totalDividas' => $totalDividas]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|max:20|unique:clientes,cpf_cnpj',
            'imagem_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'observacao' => 'nullable|string',
            'enderecos' => 'nullable|array',
            'enderecos.*.rua' => 'required_with:enderecos|string|max:255',
            'enderecos.*.numero' => 'required_with:enderecos|string|max:20',
            'enderecos.*.bairro' => 'required_with:enderecos|string|max:100',
            'enderecos.*.cidade' => 'required_with:enderecos|string|max:100',
            'enderecos.*.estado' => 'required_with:enderecos|string|max:2',
            'enderecos.*.cep' => 'required_with:enderecos|string|max:9',
            'contatos' => 'nullable|array',
            'contatos.*.tipo' => ['required_with:contatos', Rule::in(['email', 'telefone'])],
            'contatos.*.titulo' => 'required_with:contatos|string|max:100',
            'contatos.*.contato' => 'required_with:contatos|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $clienteData = $request->only(['nome_completo', 'cpf_cnpj', 'observacao']);

            if ($request->hasFile('imagem_perfil')) {
                $path = $request->file('imagem_perfil')->store('clientes', 'public');
                $clienteData['imagem_perfil_path'] = $path;
            }

            $cliente = Cliente::create($clienteData);

            if ($request->filled('enderecos')) {
                foreach ($request->enderecos as $enderecoData) {
                    if (!empty($enderecoData['rua'])) {
                        $cliente->enderecos()->create($enderecoData);
                    }
                }
            }

            if ($request->filled('contatos')) {
                foreach ($request->contatos as $contatoData) {
                    if (!empty($contatoData['contato'])) {
                        $cliente->contatos()->create($contatoData);
                    }
                }
            }

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar cliente: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o cliente.')->withInput();
        }
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('enderecos', 'contatos');
        return view('Clientes.show', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cpf_cnpj' => ['required', 'string', 'max:20', Rule::unique('clientes')->ignore($cliente->id)],
            'imagem_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'observacao' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $updateData = $request->only(['nome_completo', 'cpf_cnpj', 'observacao']);

            if ($request->hasFile('imagem_perfil')) {
                if ($cliente->imagem_perfil_path) {
                    Storage::disk('public')->delete($cliente->imagem_perfil_path);
                }
                $path = $request->file('imagem_perfil')->store('clientes', 'public');
                $updateData['imagem_perfil_path'] = $path;
            }

            $cliente->update($updateData);

            DB::commit();
            return redirect()->route('clientes.show', $cliente->id)->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar cliente: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o cliente.')->withInput();
        }
    }

    public function destroy(Cliente $cliente)
    {
        DB::beginTransaction();
        try {
            if ($cliente->imagem_perfil_path) {
                Storage::disk('public')->delete($cliente->imagem_perfil_path);
            }
            $cliente->delete();
            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir cliente: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao excluir o cliente.');
        }
    }
}
