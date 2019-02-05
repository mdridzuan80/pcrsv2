<?php
namespace App;

use Carbon\Carbon;
use App\Abstraction\Eventable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use CoenJacobs\EloquentCompositePrimaryKeys\HasCompositePrimaryKey;

class Kehadiran extends Eventable
{
    use HasCompositePrimaryKey;

    //const FLAG_SMS = 0;
    const PUNCH_IN = "IN";
    //const FLAG_NON_SMS = 1;
    const PUNCH_OUT = "OUT";
    const PUNCH_MIN = "MIN";
    const PUNCH_MOUT = "MOUT";
    const FLAG_TATATERTIB_CLEAR = "C";
    const FLAG_TATATERTIB_TUNJUK_SEBAB = "TS";

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = ['CHECKTIME'];

    public function __construct()
    {
        $this->table = $this->appDbSchema . 'CHECKINOUT';
        $this->primaryKey = ['USERID', 'CHECKTIME'];
        $this->incrementing = false;
        $this->setDateFormat(config('pcrs.modelDateFormat'));
    }

    public function scopeRekodByMulaTamat($query, Carbon $tkhMula, Carbon $tkhTamat)
    {
        return $query->where('CHECKTIME', '>=', $tkhMula)
            ->where('CHECKTIME', '<', $tkhTamat);
    }

    public function scopeEvents($query)
    {
        return $query->select(DB::raw('\'IN: \' + ISNULL(RIGHT(convert(varchar, CHECKTIME, 100),7), \'-\') + CHAR(10) + \'OUT: -\' as [title]'), DB::raw('\'' . today() . '\' as [start]'), DB::raw('\'' . today() . '\' as [end]'), DB::raw('\'true\' as [allDay]'), DB::raw('\'#dcf442\' as [color]'), DB::raw('\'#000\' as [textColor]'), DB::raw('0 as [id] '), DB::raw('\'' . Eventable::CURRENTATT . '\' as [table_name]'));
    }

    public function scopeToday($query)
    {
        return $query->whereBetween('CHECKTIME', [today()->addHours(4), today()->addHours(13)]);
    }

    public static function itemEventableNone()
    {
        return [
            'title' => 'IN: -' . "\n" . 'OUT: -',
            'start' => today()->toDateTimeString(),
            'end' => today()->toDateTimeString(),
            'allDay' => 'true',
            'color' => '#1abc9c',
            'textColor' => '#000',
            'id' => 0,
            'table_name' => 'current'
        ];
    }
}
