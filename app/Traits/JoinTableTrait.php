<?php 

namespace App\Traits;

// use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

trait JoinTableTrait
{
    /**
     * @param string $relation - The relation to create the query for
     * @param string|null $overwrite_table - In case if you want to overwrite the table (join as)
     * @return Builder
    */
    public static function RelationToJoin(string $relation, $overwrite_table = false) {
        $instance = (new self());
        if(!method_exists($instance, $relation))
            throw new \Error('Method ' . $relation . ' does not exists on class ' . self::class);
        $relationData = $instance->{$relation}();
        if(gettype($relationData) !== 'object')
            throw new \Error('Method ' . $relation . ' is not a relation of class ' . self::class);
        if(!is_subclass_of(get_class($relationData), Relation::class))
            throw new \Error('Method ' . $relation . ' is not a relation of class ' . self::class);
        $related = $relationData->getRelated();
        $me = new self();
        $query = $relationData->getQuery()->getQuery();
        switch(get_class($relationData)) {
            case HasOne::class:
                $keys = [
                    'foreign' => $relationData->getForeignKeyName(),
                    'local' => $relationData->getLocalKeyName()
                ];
            break;
            case BelongsTo::class:
                $keys = [
                    'foreign' => $relationData->getOwnerKeyName(),
                    'local' => $relationData->getForeignKeyName()
                ];
            break;
            default:
                throw new \Error('Relation join only works with one to one relationships');
        }
        $checks = [];
        $other_table = ($overwrite_table ? $overwrite_table : $related->getTable());
        foreach($keys as $key) {
            array_push($checks, $key);
            array_push($checks, $related->getTable() . '.' . $key);
        }
        foreach($query->wheres as $key => $where)
            if(in_array($where['type'], ['Null', 'NotNull']) && in_array($where['column'], $checks))
                unset($query->wheres[$key]);
        $query = $query->whereRaw('`' . $other_table . '`.`' . $keys['foreign'] . '` = `' . $me->getTable() . '`.`' . $keys['local'] . '`');
        return (object) [
            'query' => $query,
            'table' => $related->getTable(),
            'wheres' => $query->wheres,
            'bindings' => $query->bindings
        ];
    }

    /**
     * @param Builder $builder
     * @param string $relation - The relation to join
    */
    public function scopeJoinRelation(Builder $query, string $relation) {
        $join_query = self::RelationToJoin($relation, $relation);
        $query->join($join_query->table . ' AS ' . $relation, function(JoinClause $builder) use($join_query) {
            return $builder->mergeWheres($join_query->wheres, $join_query->bindings);
        });
        return $query;
    }
}