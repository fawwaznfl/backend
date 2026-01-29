<?php 

namespace App\Http\Controllers\Api\Performance;
 
use App\Http\Controllers\Controller; 
use App\Models\DetailTarget; 
use App\Http\Requests\StoreDetailTargetRequest; 
use App\Http\Requests\UpdateDetailTargetRequest; 
use ApiFormatter;
use App\Helpers\ApiFormatter as HelpersApiFormatter;

class DetailTargetController extends Controller {

    public function index(){ 
        return HelpersApiFormatter::success(DetailTarget::with('target')->orderBy('id','desc')->get(),'DetailTargets fetched'); 
    }

    public function store(StoreDetailTargetRequest $r){ 
        $payload=$r->validated(); 
        $payload['created_by']=auth()->id() ?? null; 
        $d=DetailTarget::create($payload); 
            return HelpersApiFormatter::success($d,'Created',201); 
    }

    public function show($id){ 
        $d=DetailTarget::find($id); if(!$d) 
            return HelpersApiFormatter::error('Not found',404); 
            return HelpersApiFormatter::success($d,'Found'); 
    }

    public function update(UpdateDetailTargetRequest $r,$id){
         $d=DetailTarget::find($id); if(!$d) 
            return HelpersApiFormatter::error('Not found',404); 
        
        $payload=$r->validated(); 
        $payload['updated_by']=auth()->id() ?? null; 
        $d->update($payload); 
            return HelpersApiFormatter::success($d,'Updated'); 
    }

    public function destroy($id){ 
        $d=DetailTarget::find($id); if(!$d) 
            return HelpersApiFormatter::error('Not found',404); $d->delete(); 
            return HelpersApiFormatter::success(null,'Deleted',204); 
    }
}
