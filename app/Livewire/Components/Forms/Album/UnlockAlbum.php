<?php

namespace App\Livewire\Components\Forms\Album;

use App\Actions\Album\Unlock;
use App\Factories\AlbumFactory;
use App\Http\RuleSets\Album\UnlockAlbumRuleSet;
use App\Livewire\Traits\Notify;
use App\Livewire\Traits\UseValidator;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UnlockAlbum extends Component
{
	use AuthorizesRequests;
	use UseValidator;
	use Notify;

	#[Locked] public string $albumID;
	public string $password = ''; // ! wired

	private Unlock $unlock;
	private AlbumFactory $albumFactory;

	/**
	 * Initialize private attributes.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		$this->unlock = resolve(Unlock::class);
		$this->albumFactory = resolve(AlbumFactory::class);
	}

	/**
	 * This is the equivalent of the constructor for Livewire Components.
	 *
	 * @param $albumID album to unlock
	 *
	 * @return void
	 */
	public function mount(string $albumID): void
	{
		$this->albumID = $albumID;
	}

	/**
	 * Simply render the form.
	 *
	 * @return View
	 */
	public function render(): View
	{
		return view('livewire.forms.album.unlock-album');
	}

	/**
	 * Method called from the front-end to unlock the album when given a password.
	 * This will throw an exception on failure!
	 *
	 * @return void
	 */
	public function submit(): void
	{
		if (!$this->areValid(UnlockAlbumRuleSet::rules())) {
			return;
		}

		$album = $this->albumFactory->findBaseAlbumOrFail($this->albumID);
		$this->unlock->do($album, $this->password);
		$this->redirect(route('livewire-gallery-album', ['albumId' => $this->albumID]));
	}
}
