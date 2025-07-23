<?php

namespace App\DataTables;

use App\Models\PresenceDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PresenceDetailsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('waktu_absen', function ($query) {
                return date('d-m-Y H:i:s', strtotime($query->created_at));
            })
            ->addColumn('tanda_tangan', function ($query) {
                return "<img width='100' src='" . asset('uploads/' . $query->tanda_tangan) . "'>";
            })
            ->addColumn('action', function ($query) {
                $btnDelete = "<a href='" . route('presence-detail.destroy', $query->id) . "' class='btn btn-delete btn-danger'>Hapus</a>";

                return "{$btnDelete}";
            })
            ->rawColumns(['tanda_tangan', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PresenceDetail $model): QueryBuilder
    {
        return $model->with('presence')->where('presence_id', request()->segment(2))->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('presencedetails-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->title('#')
                ->render('meta.row + meta.settings._iDisplayStart + 1;')
                ->width(100),
            Column::make('presence.nama_kegiatan')->title('Nama Kegiatan'),
            Column::make('waktu_absen'),
            Column::make('nama'),
            Column::make('jabatan'),
            Column::make('asal_instansi'),
            Column::make('no_telp')->title('No. Telepon'),
            Column::make('tanda_tangan'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }


    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PresenceDetails_' . date('YmdHis');
    }
}
