<?php

namespace Rogue\Models\Traits;

trait HasCursor
{
    /**
     * Create a cursor for this item.
     *
     * @return string
     */
    public function getCursor()
    {
        $sortCursor = '';

        // If we're ordering results, we need to include that column's
        // value in the cursor (so we can say "get me things after this"):
        if ($orderBy = request()->query('orderBy')) {
            [ $column, $direction ] = explode(',', $orderBy, 2);

            // Check that it's okay to expose this column in the cursor:
            if (in_array($column, self::$sortable)) {
                $sortCursor = '.' . $this->{$column};
            }
        }

        return base64_encode($this->id . $sortCursor);
    }

    /**
     * Scope a query to only include items after the given cursor.
     */
    public function scopeWhereAfterCursor($query, $cursor, $defaultSort = 'id,desc')
    {
        $cursor = explode('.', base64_decode($cursor), 2);

        $id = $cursor[0];
        $sortCursor = isset($cursor[1]) ? $cursor[1] : null;

        $orderBy = request()->query('orderBy');
        if ($orderBy && $sortCursor) {
            // If we're sorting by a column, things get a lil' tricky:
            [ $column, $direction ] = explode(',', $orderBy, 2);
            $operator = $direction === 'asc' ? '>' : '<';

            // Check that we're allowed to sort by this column:
            if (in_array($column, self::$sortable)) {
                $query->where($column, $operator, $sortCursor)
                    ->orWhere(function ($query) use ($column, $operator, $sortCursor, $id) {
                        $query->where($column, $operator.'=', $sortCursor)
                            ->where('id', '>', $id);
                    });
            }
        } else {
            // Otherwise, treat as a plain ID cursor... easy!
            $query->where('id', '>', $id);
        }
}
