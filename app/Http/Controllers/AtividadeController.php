<?php

namespace App\Http\Controllers;

use App\Atividade;
use App\mensagens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AtividadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaAtividades = Atividade::paginate(3);
        return view('atividade.list',['atividades' => $listaAtividades]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('atividade.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'title.required' => 'É obrigatório um título para a atividade',
            'description.required' => 'É obrigatória uma descrição para a atividade',
            'scheduledto.required' => 'É obrigatório o cadastro da data/hora da atividade',
            );
            //vetor com as especificações de validações
            $regras = array(
            'title' => 'required|string|max:255',
            'description' => 'required',
            'scheduledto' => 'required|string',
            );
            //cria o objeto com as regras de validação
            $validador = Validator::make($request->all(), $regras, $messages);
            //executa as validações
            if ($validador->fails()) {
            return redirect('atividades/create')
            ->withErrors($validador)
            ->withInput($request->all);
            }
            //se passou pelas validações, processa e salva no banco...
            $obj_Atividade = new Atividade();
            $obj_Atividade->title = $request['title'];
            $obj_Atividade->description = $request['description'];
            $obj_Atividade->scheduledto = $request['scheduledto'];
            $obj_Atividade->save();
            return redirect('/atividades')->with('success', 'Atividade criada com sucesso!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $atividade = Atividade::find($id)->with('mensagens')->get()->first();
        return view('atividade.show',['atividade' => $atividade]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $obj_atividades = Atividade::find($id);
        return view('atividade.edit',['atividades' => $obj_atividades]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = array(
            'title.required' => 'É obrigatório um título para a atividade',
            'description.required' => 'É obrigatória uma descrição para a atividade',
            'scheduledto.required' => 'É obrigatório o cadastro da data/hora da atividade',
            );
            //vetor com as especificações de validações
            $regras = array(
            'title' => 'required|string|max:255',
            'description' => 'required',
            'scheduledto' => 'required|string',
            );
            //cria o objeto com as regras de validação
            $validador = Validator::make($request->all(), $regras, $messages);
            //executa as validações
            if ($validador->fails()) {
                return redirect('atividades/$id/edit')
            ->withErrors($validador)
            ->withInput($request->all);
            }
            //se passou pelas validações, processa e salva no banco...
            $obj_Atividade = Atividade::findOrFail($id);
            $obj_Atividade->title = $request['title'];
            $obj_Atividade->description = $request['description'];
            $obj_Atividade->scheduledto = $request['scheduledto'];
            $obj_Atividade->save();
            return redirect('/atividades')->with('success', 'Atividade criada com sucesso!!');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj_Atividade = Atividade::findOrFail($id);
        $obj_Atividade->delete($id);
        return redirect('/atividades')->with('success','Atividade excluída com Sucesso!!');
    }

    public function delete($id)
    {
        $obj_Atividade = Atividade::find($id);
        return view('atividade.delete',['atividade' => $obj_Atividade]);
    }
}
