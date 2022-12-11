<?php

namespace App\Tables;

use Illuminate\Support\Facades\DB;


class Table {
    protected $table;

    public function index() {
        return DB::table($this->table)->get();
    }

    public function show($id) {
        return DB::table($this->table)->where('id', $id)->first();
    }

    public function store($data) {
        return DB::table($this->table)->insert($data);
    }

    public function update($id, $data) {
        return DB::table($this->table)->where('id', $id)->update($data);
    }

    public function destroy($id) {
        return DB::table($this->table)->where('id', $id)->delete();
    }
}