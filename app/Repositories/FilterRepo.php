<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FilterRepo
{
    public function dataFilters(){

        $mainDistricts = array(
            222 => 'Riverndale', 159 => 'Howard Beach', 200 => 'Rockway',

        );

        /*$filters_data = DB::table('filters')
            ->leftjoin('filters_data', 'filters.name', '=', 'filters_data.filter_id')
            ->get();*/

	    $filters_data = DB::select("select filters.*, filters_data.*, if (filters.name in ('sub_districts'), left(filters_data.value, 10), '0') as sort from filters left join filters_data on filters.name = filters_data.filter_id order by filters.id, sort, filters_data.filter_data_id");

        $filters = array();
        $id = array();
        $result = array();
        $districts = array();
        $types = array();

        $subBoro = array();
        $counts = array();

        foreach($filters_data as $key=>$item){
            if ($item->filter_id == 'type'){
                $types[$item->filter_data_id]= $item;
            }

            if ($item->filter_id == 'filters'){
                $filters[$item->filter_data_id]= $item;
            }
            $subDistrictsCol = array('left' => array(), 'right' => array(), 'center' => array());
            if ($item->filter_id == 'district' && $item->filter_data_id){
                $item->subdistritcs = array();
                $item->subdistritcsCol = $subDistrictsCol;
                $districts[$item->filter_data_id]= $item;
            }

            if ($item->filter_id == 'sub_districts'){
                if ($item->parent_id!=0){
                    if (isset($counts[$item->parent_id])){
                        $counts[$item->parent_id] =  $counts[$item->parent_id]+1;
                    }else {
                        $counts[$item->parent_id] = 1;
                    }

                    if ($counts[$item->parent_id]%3==1){
                        $item->rigth = false;
                        $item->left = true;
                        $item->center = false;
                    }elseif ($counts[$item->parent_id]%3==2){
                        $item->rigth = false;
                        $item->left = false;
                        $item->center = true;
                    }else{
                        $item->rigth = true;
                        $item->left = false;
                        $item->center = false;
                    }
                    $item->mainboro = false;

                    if (array_key_exists($item->filter_data_id,$mainDistricts)){
                        $item->mainboro = true;
                        $subBoro[$item->district_id] = array();
                    }

                    if ($item->district_id && !array_key_exists($item->filter_data_id,$mainDistricts) && $item->parent_id != 1)
                    {
                        $subBoro[$item->district_id][] = $item;
                    }
                    else
                    {
                        if ($item->parent_id == 1){
                            $districts[$item->parent_id]->subdistritcs[$item->district_id][] = $item;
                        }else {
                            $districts[$item->parent_id]->subdistritcs[] = $item;
                        }
                    }
                }
            }

            $data = (array) $item;

            if (in_array($data['id'], $id)){

                $result[$data['id']]['sub_filters'][] = [

                    'value'=>$data['value'],
                    'parent_id'=>$data['parent_id']
                ];
            } else {

                $result[$data['id']] = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'label' => $data['label'],
                    'general' => $data['general'],
                    'sub_filters' => [[
                        'value'=>$data['value'],
                        'parent_id'=>$data['parent_id']
                    ]]];
                $id[] = $data['id'];
            }
        }

        $subBorougths = $subBoro;

        return compact('result','districts', 'types', 'filters','subBorougths');
    }

    public function prepareSaveListingFilters($types, $district, $filters){

/*print_r($types); echo "<br>";

print_r($district);

print_r($filters);

echo DB::table('filters_data')
->where('filter_data_id',$types)
->orWhere('filter_data_id', $district)
->orWhereIn('filter_data_id', $filters)
->toSql();
exit;*/

        $data = DB::table('filters_data')
            ->where('filter_data_id',$types)
            ->orWhere('filter_data_id', $district)
            ->orWhereIn('filter_data_id', $filters)
            ->get();

        $amenities_list = array();
        $features_list = array();
        foreach ($data as $item){
            if($item->filter_id == 'type'){
                $type = $item->value;
            }
            if($item->filter_id == 'filters' && $item->parent_id == 6){
                $features_list[] = $item->value;
            }
            if($item->filter_id == 'filters' && $item->parent_id == 0){
                $amenities_list[] = $item->value;
            }
            if($item->filter_id == 'sub_districts'){
                $neighborhood = $item->value;
            }
        }

        return compact('type', 'features_list', 'amenities_list', 'neighborhood');
    }

    public function prepareEditListingData($amenities, $futures)
    {
        return DB::table('filters_data')
            ->select('filter_data_id')
            ->orWhereIn('value', $amenities)
            ->orWhereIn('value', $futures)
            ->get();
    }
}
