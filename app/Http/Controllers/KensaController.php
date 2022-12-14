<?php

namespace App\Http\Controllers;

use App\Models\kensa;
use App\Models\MasterData;
use App\Models\Pengiriman;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class KensaController extends Controller
{
    //tampil data
    public function index()
    {
        $kensa = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->select('kensa.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->orderBy('tanggal_k', 'desc')->orderBy('waktu_k', 'desc')
            ->get();

        $sum_qty_bar = DB::table('kensa')->get()->sum('qty_bar');
        $sum_nikel = DB::table('kensa')->get()->sum('nikel');
        $sum_butsu = DB::table('kensa')->get()->sum('butsu');
        $sum_hadare = DB::table('kensa')->get()->sum('hadare');
        $sum_hage = DB::table('kensa')->get()->sum('hage');
        $sum_moyo = DB::table('kensa')->get()->sum('moyo');
        $sum_fukure = DB::table('kensa')->get()->sum('fukure');
        $sum_crack = DB::table('kensa')->get()->sum('crack');
        $sum_henkei = DB::table('kensa')->get()->sum('henkei');
        $sum_hanazaki = DB::table('kensa')->get()->sum('hanazaki');
        $sum_kizu = DB::table('kensa')->get()->sum('kizu');
        $sum_kaburi = DB::table('kensa')->get()->sum('kaburi');
        $sum_other = DB::table('kensa')->get()->sum('other');
        $sum_gores = DB::table('kensa')->get()->sum('gores');
        $sum_regas = DB::table('kensa')->get()->sum('regas');
        $sum_silver = DB::table('kensa')->get()->sum('silver');
        $sum_hike = DB::table('kensa')->get()->sum('hike');
        $sum_burry = DB::table('kensa')->get()->sum('burry');
        $sum_others = DB::table('kensa')->get()->sum('others');
        $sum_total_ok = DB::table('kensa')->get()->sum('total_ok');
        $sum_total_ng = DB::table('kensa')->get()->sum('total_ng');
        $avg_p_total_ok = DB::table('kensa')->get()->average('p_total_ok');
        $avg_p_total_ng = DB::table('kensa')->get()->average('p_total_ng');

        $masterdata = MasterData::all();

        return view('kensa.kensa-index', compact(
            'kensa',
            'masterdata',
            'sum_qty_bar',
            'sum_nikel',
            'sum_butsu',
            'sum_hadare',
            'sum_hage',
            'sum_moyo',
            'sum_fukure',
            'sum_crack',
            'sum_henkei',
            'sum_hanazaki',
            'sum_kizu',
            'sum_kaburi',
            'sum_other',
            'sum_gores',
            'sum_regas',
            'sum_silver',
            'sum_hike',
            'sum_burry',
            'sum_others',
            'sum_total_ok',
            'sum_total_ng',
            'avg_p_total_ok',
            'avg_p_total_ng'
        ));
    }

    //tambah data
    public function tambah()
    {
        $kensa = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->select('kensa.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->orderBy('tanggal_k', 'desc')
            ->get();

        $masterdata = MasterData::all();
        return view('kensa.kensa-tambah', compact('kensa', 'masterdata'));
    }

    //simpan data
    public function simpan(Request $request)
    {
        kensa::create([
            'tanggal_k' => $request->tanggal_k,
            'waktu_k' => $request->waktu_k,
            'id_masterdata' => $request->id_masterdata,
            'no_part' => $request->no_part,
            'part_name' => $request->part_name,
            'no_bar' => $request->no_bar,
            'qty_bar' => $request->qty_bar,
            'cycle' => $request->cycle,
            'nikel' => $request->nikel,
            'butsu' => $request->butsu,
            'hadare' => $request->hadare,
            'hage' => $request->hage,
            'moyo' => $request->moyo,
            'fukure' => $request->fukure,
            'crack' => $request->crack,
            'henkei' => $request->henkei,
            'hanazaki' => $request->hanazaki,
            'kizu' => $request->kizu,
            'kaburi' => $request->kaburi,
            'other' => $request->other,
            'gores' => $request->gores,
            'regas' => $request->regas,
            'silver' => $request->silver,
            'hike' => $request->hike,
            'burry' => $request->burry,
            'others' => $request->others,
            'total_ok' => $request->total_ok,
            'total_ng' => $request->total_ng,
            'p_total_ok' => $request->p_total_ok,
            'p_total_ng' => $request->p_total_ng
        ]);
        $masterdata = MasterData::find($request->id_masterdata);
        $masterdata->stok += $request->total_ok;
        $masterdata->total_ng += $request->total_ng;
        $masterdata->total_ok += $request->total_ok;
        $masterdata->save();

        return redirect()->route('kensa.tambah')->with('toast_success', 'Data berhasil disimpan');
    }

    //edit data
    public function edit($id)
    {
        // return view('racking.racking-edit',compact('racking'));
        $kensa = DB::table('kensa')->where('kensa_id', $id)->first();
        return view('kensa.kensa-edit', ['kensa' => $kensa]);
    }

    //hapus data
    public function delete($id)
    {
        $kensa = kensa::find($id);
        $kensa->delete();
        return redirect('kensa')->with('toast_success', 'Data berhasil dihapus');
        // DB::table('kensa')
        //     ->select('kensa.kensa_id')->where('kensa_id', $id)->delete();
        // return redirect()->back()->with('message', 'Data berhasil dihapus');
    }

    //update data
    public function update(Request $request, $id)
    {
        $kensa = kensa::find($id);

        $kensa->tanggal_k = $request->tanggal_k;
        $kensa->waktu_k = $request->waktu_k;
        $kensa->no_part = $request->no_part;
        $kensa->part_name = $request->part_name;
        $kensa->no_bar = $request->no_bar;
        $kensa->qty_bar = $request->qty_bar;
        $kensa->cycle = $request->cycle;
        $kensa->nikel = $request->nikel;
        $kensa->butsu = $request->butsu;
        $kensa->hadare = $request->hadare;
        $kensa->hage = $request->hage;
        $kensa->moyo = $request->moyo;
        $kensa->fukure = $request->fukure;
        $kensa->crack = $request->crack;
        $kensa->henkei = $request->henkei;
        $kensa->hanazaki = $request->hanazaki;
        $kensa->kizu = $request->kizu;
        $kensa->kaburi = $request->kaburi;
        $kensa->other = $request->other;
        $kensa->gores = $request->gores;
        $kensa->regas = $request->regas;
        $kensa->silver = $request->silver;
        $kensa->hike = $request->hike;
        $kensa->burry = $request->burry;
        $kensa->others = $request->others;
        $kensa->total_ok = $request->total_ok;
        $kensa->total_ng = $request->total_ng;
        $kensa->p_total_ok = $request->p_total_ok;
        $kensa->p_total_ng = $request->p_total_ng;

        $kensa->save();
        // alert()->success('SuccessAlert', 'Lorem ipsum dolor sit amet.');
        return redirect()->route('kensa.tambah')->with('message', 'Data berhasil di update');
    }

    public function autocomplete($id)
    {
        if (empty($id)) {
            return [];
        }
        $datas = DB::table('masterdata')
            ->join('kensa', 'kensa.no_part', '=', 'masterdata.no_part')
            ->where('masterdata.part_name', 'LIKE', "$id%")
            ->limit(25)
            ->get();

        return $datas;
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $kensa = kensa::where('part_name', 'like', "%" . $keyword . "%")->paginate(124);
        return view('kensa.kensa-index', compact('kensa'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function searchDater(Request $request)
    {
        if (request()->start_date || request()->end_date) {
            $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
            $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
            $kensa = kensa::whereBetween('tanggal_k', [$start_date, $end_date])->paginate(75);
        } else {
            $kensa = kensa::latest()->paginate(75);
        }
        return view('kensa.kensa-index', compact('kensa'));
    }

    public function printKanban()
    {
        $pengiriman = Pengiriman::join('masterdata', 'masterdata.id', '=', 'pengiriman.id_masterdata')
            ->select('pengiriman.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->get();

        $masterdata = MasterData::all();

        $q = DB::table('pengiriman')->select(DB::raw('MAX(RIGHT(no_kartu,4)) as kode'));
        $kode = "";
        if ($q->count() > 0) {
            foreach ($q->get() as $k) {
                $tmp = ((int)$k->kode) + 1;
                $kode = sprintf("%04s", $tmp);
            }
        } else {
            $kode = "0001";
        }
        // return "NBM-".$kd;

        return view('kensa.print-kanban', compact('pengiriman', 'masterdata', 'kode'));
    }

    public function ajax(Request $request)
    {
        $id_masterdata['id_masterdata'] = $request->id_masterdata;
        $ajax_barang = MasterData::where('id', $id_masterdata)->get();

        return view('kensa.kensa-ajax', compact('ajax_barang'));
    }

    public function ajaxKanban(Request $request)
    {
        $id_masterdata['id_masterdata'] = $request->id_masterdata;
        $ajax_barang = MasterData::where('id', $id_masterdata)->get();

        return view('kensa.print-kanban-ajax', compact('ajax_barang'));
    }

    public function kanbansimpan(Request $request)
    {
        $masterdata = MasterData::find($request->id_masterdata);

        if ($masterdata->stok < $request->kirim_assy) {
            return redirect()->route('kensa.printKanban')->with('toast_error', 'Gagal!, Stok Kurang');
        } else if ($masterdata->stok < $request->kirim_painting) {
            return redirect()->route('kensa.printKanban')->with('toast_error', 'Gagal!, Stok Kurang');
        } else {
            Pengiriman::create([
                'tgl_kanban' => $request->tgl_kanban,
                'id_masterdata' => $request->id_masterdata,
                'no_part' => $request->no_part,
                'part_name' => $request->part_name,
                'model' => $request->model,
                'bagian' => $request->bagian,
                'qty_troly' => $request->qty_troly,
                'total_kirim' => $request->total_kirim,
                'no_kartu' => $request->no_kartu,
                'next_process' => $request->next_process,
                'kirim_painting' => $request->kirim_painting,
                'kirim_assy' => $request->kirim_assy,
            ]);

            $masterdata->stok -= $request->kirim_assy;
            $masterdata->total_ok -= $request->kirim_assy;
            $masterdata->stok -= $request->kirim_painting;
            $masterdata->total_ok -= $request->kirim_painting;
            $masterdata->kirim_assy += $request->kirim_assy;
            $masterdata->kirim_painting += $request->kirim_painting;
            $masterdata->no_kartu = $request->no_kartu;
            $masterdata->save();

            return redirect()->route('kensa.printKanban')->with('toast_success', 'Data berhasil disimpan');
        }
    }

    public function export()
    {
        $data = PDF::loadview('kensa.print_kanban_pdf', ['data' => 'ini adalah contoh laporan PDF']);
        return $data->download('kanban.pdf');
    }

    public function cetak_kanban(Request $request, $id)
    {
        $pengiriman = Pengiriman::where('id', $id)->first();
        $masterdata = MasterData::all();
        return view('kensa.cetak-kanban', compact('pengiriman', 'masterdata'));
    }

    public function pengiriman()
    {
        $pengiriman = Pengiriman::join('masterdata', 'masterdata.id', '=', 'pengiriman.id_masterdata')
            ->select('pengiriman.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->get();
        $masterdata = MasterData::all();
        return view('kensa.pengiriman-index', compact('pengiriman', 'masterdata'));
    }

    public function utama()
    {
        // $moyo = kensa::select('moyo')->count();
        // return view('ikan')->with('moyo', $moyo);

        $date = date('d-m-Y',strtotime("-1 days"));

        $sum_qty_bar = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('qty_bar');
        $sum_total_ng = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('total_ng');
        $sum_nikel = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('nikel');
        $nikel = $sum_nikel != 0 && $sum_qty_bar != 0 ? (($sum_nikel / $sum_qty_bar) * 100) : 0;
        $sum_butsu = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('butsu');
        $butsu = $sum_butsu != 0 && $sum_qty_bar != 0 ? (($sum_butsu / $sum_qty_bar) * 100) : 0;
        $sum_hadare = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('hadare');
        $hadare = $sum_hadare != 0 && $sum_qty_bar != 0 ? (($sum_hadare / $sum_qty_bar) * 100) : 0;
        $sum_hage = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('hage');
        $hage = $sum_hage != 0 && $sum_qty_bar != 0 ? (($sum_hage / $sum_qty_bar) * 100) : 0;
        $sum_moyo = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('moyo');
        $moyo = $sum_moyo != 0 && $sum_qty_bar != 0 ? (($sum_moyo / $sum_qty_bar) * 100) : 0;
        $sum_fukure = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('fukure');
        $fukure = $sum_fukure != 0 && $sum_qty_bar != 0 ? (($sum_fukure / $sum_qty_bar) * 100) : 0;
        $sum_crack = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('crack');
        $crack = $sum_crack != 0 && $sum_qty_bar != 0 ? (($sum_crack / $sum_qty_bar) * 100) : 0;
        $sum_henkei = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('henkei');
        $henkei = $sum_henkei != 0 && $sum_qty_bar != 0 ? (($sum_henkei / $sum_qty_bar) * 100) : 0;
        $sum_hanazaki = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('hanazaki');
        $hanazaki = $sum_hanazaki != 0 && $sum_qty_bar != 0 ? (($sum_hanazaki / $sum_qty_bar) * 100) : 0;
        $sum_kizu = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('kizu');
        $kizu = $sum_kizu != 0 && $sum_qty_bar != 0 ? (($sum_kizu / $sum_qty_bar) * 100) : 0;
        $sum_kaburi = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('kaburi');
        $kaburi = $sum_kaburi != 0 && $sum_qty_bar != 0 ? (($sum_kaburi / $sum_qty_bar) * 100) : 0;
        $sum_other = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('other');
        $other = $sum_other != 0 && $sum_qty_bar != 0 ? (($sum_other / $sum_qty_bar) * 100) : 0;
        $sum_gores = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('gores');
        $gores = $sum_gores != 0 && $sum_qty_bar != 0 ? (($sum_gores / $sum_qty_bar) * 100) : 0;
        $sum_regas = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('regas');
        $regas = $sum_regas != 0 && $sum_qty_bar != 0 ? (($sum_regas / $sum_qty_bar) * 100) : 0;
        $sum_silver = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('silver');
        $silver = $sum_silver != 0 && $sum_qty_bar != 0 ? (($sum_silver / $sum_qty_bar) * 100) : 0;
        $sum_hike = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('hike');
        $hike = $sum_hike != 0 && $sum_qty_bar != 0 ? (($sum_hike / $sum_qty_bar) * 100) : 0;
        $sum_burry = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('burry');
        $burry = $sum_burry != 0 && $sum_qty_bar != 0 ? (($sum_burry / $sum_qty_bar) * 100) : 0;
        $sum_others = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('others');
        $others = $sum_others != 0 && $sum_qty_bar != 0 ? (($sum_others / $sum_qty_bar) * 100) : 0;
        $sum_total_ok = DB::table('kensa')->where('tanggal_k', '=', $date)->get()->sum('total_ok');
        $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
        $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;
        $kensa_today = kensa::where('tanggal_k', '=', $date)->count();

        return view('kensa.kensa_menu_utama', compact(
            'nikel',
            'sum_nikel',
            'butsu',
            'sum_butsu',
            'hadare',
            'sum_hadare',
            'hage',
            'moyo',
            'fukure',
            'crack',
            'henkei',
            'hanazaki',
            'kizu',
            'kaburi',
            'other',
            'gores',
            'sum_gores',
            'regas',
            'silver',
            'hike',
            'burry',
            'others',
            'total_ok',
            'total_ng',
            'date',
            'sum_qty_bar',
            'kensa_today'
        ));
    }
}
