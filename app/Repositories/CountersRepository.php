<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Counters;
use App\Interfaces\CountersRepositoryInterface;

class CountersRepository implements CountersRepositoryInterface
{
    public function all()
    {
        return Counters::all();
    }

    public function create($data)
    {
        return Counters::create($data);
    }

    public function update($data, $id)
    {
        $get = Counters::findOrFail($id);
        $get->update($data);
        return $get;
    }

    public function delete($id)
    {
        $get = Counters::findOrFail($id);
        $get->delete();
    }

    public function find($id)
    {
        return Counters::findOrFail($id);
    }

    public function incrementCounter($collectionName, $columnName, $paramsAdditional = [])
    {
        $counter = Counters::where('collection_name', $collectionName)
            ->where('column_name', $columnName);

        if (!empty($paramsAdditional['code'])) {
            $counter = $counter->where('code', $paramsAdditional['code']);
        }

        $counter = $counter->first();

        if (!$counter) {
            $sequenceValue = 0;
            if (!empty($paramsAdditional['start_from'])) {
                $sequenceValue = $paramsAdditional['start_from'];
            }

            $counter = new Counters;
            $counter->collection_name = $collectionName;
            $counter->column_name = $columnName;
            $counter->code = $code ?? ''; // Set code if provided.
            $counter->sequence_value = $sequenceValue; // Initialize to 0, will increment next.
            $counter->save();
        }

        // Increment the sequenceValue.
        $counter->increment('sequence_value');
        $newSequenceValue = $counter->sequence_value;

        if (!empty($counter->code)) {
            $padLength = 5;
            if(!empty($paramsAdditional['pad_length'])){
                $padLength = $paramsAdditional['pad_length'];
            }

            $numberPart = str_pad($newSequenceValue,
            $padLength - strlen($counter->code), '0', STR_PAD_LEFT);
            return $counter->code . '' . $numberPart;
        } else {
            return (string) $newSequenceValue;
        }
    }
}
