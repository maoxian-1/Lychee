<?php

namespace App\Livewire\Components\Modules\Diagnostics;

use App\Actions\Diagnostics\Errors as DiagnosticsErrors;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Errors extends Component
{
	#[Locked] public string $title = 'Diagnostics';
	#[Locked] public string $error_msg = 'No critical problems found. Lychee should work without problems!';
	public function placeholder(): string
	{
		return '<p class="font-mono">
	Diagnostics
	-----------
	<span class="text-primary-500 font-bold">    ' . __('lychee.LOADING') . ' ...</span>
</p>
';
	}

	/**
	 * Computable property to access the errors.
	 * If we are not ready to load, we return an empty array.
	 *
	 * @return array
	 */
	public function getDataProperty(): array
	{
		return collect(resolve(DiagnosticsErrors::class)->get())->map(function ($line) {
			$arr = ['color' => '', 'type' => '', 'line' => $line];

			if (Str::startsWith($line, 'Warning: ')) {
				$arr['color'] = 'text-warning-600';
				$arr['type'] = 'Warning:';
				$arr['line'] = Str::substr($line, 9);
			}

			if (Str::startsWith($line, 'Info: ')) {
				$arr['color'] = 'text-primary-400';
				$arr['type'] = 'Info:';
				$arr['line'] = Str::substr($line, 6);
			}

			if (Str::startsWith($line, 'Error: ')) {
				// @codeCoverageIgnoreStart
				$arr['color'] = 'text-danger-600';
				$arr['type'] = 'Error:';
				$arr['line'] = Str::substr($line, 7);
				// @codeCoverageIgnoreEnd
			}

			return $arr;
		})->all();
	}

	/**
	 * Rendering of the front-end.
	 *
	 * @return View
	 */
	final public function render(): View
	{
		return view('livewire.modules.diagnostics.pre-colored');
	}
}
