<span>{{ \Carbon\Carbon::parse($date)->format($format?? config('livewire-datatable.date_format', 'd/m/Y')) }}</span>