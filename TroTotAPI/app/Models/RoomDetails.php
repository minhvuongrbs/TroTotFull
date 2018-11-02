<?phpRoutes

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomDetails extends Model
{
    public $timestamps=false;
    protected $table='rooms_detail';
    public function post_room()
    {
      return $this->belongsTo('App\Models\PostRoom','id','post_id');
    }

}
