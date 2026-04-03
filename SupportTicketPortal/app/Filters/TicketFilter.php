<?php
namespace App\Filters;

class TicketFilter extends BaseFilter
{
    public function status($value)
    {
        $this->query->where('status', $value);
    }

    public function priority_id($value)
    {
        $this->query->where('priority_id', $value);
    }

    public function assigned_to($value)
    {
        $this->query->where('assigned_to', $value);
    }

    public function keyword($value)
    {
        $this->query->where(function ($q) use ($value) {
            $q->where('title', 'like', "%$value%")
              ->orWhere('description', 'like', "%$value%")
              ->orWhere('status', 'like', "%$value%")

              ->orWhereHas('priority', function ($p) use ($value) {
                  $p->where('name', 'like', "%$value%");
              });
        });
    }

    public function sla_status($value)
    {
        $this->query->where('sla_status', $value);
    }

    public function date_from($value)
    {
        $this->query->whereDate('created_at', '>=', $value);
    }

    public function date_to($value)
    {
        $this->query->whereDate('created_at', '<=', $value);
    }

    public function organisation_id($value)
    {
        $this->query->whereHas('creator', function ($q) use ($value) {
            $q->where('organisation_id', $value);
        });
    }
}
