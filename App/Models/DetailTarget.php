<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DetailTarget extends Model { 
    use HasFactory; 
    protected $fillable=['target_kinerja_id','item','target','realisasi','created_by','updated_by']; 
    public function target(){ 
        return $this->belongsTo(TargetKinerja::class,'target_kinerja_id'); 
    } 
}