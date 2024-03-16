<x-app-layout>
	<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
		<form method="POST" action="{{ route('chirps.store') }}">
			@csrf
			<textarea
				name="message"
				placeholder="{{ __('What\'s on your mind?') }}"
				class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
			>{{ old('message') }}</textarea>
			<x-input-error :messages="$errors->get('message')" class="mt-2" />
			<x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
		</form>

		<div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
			@foreach ($chirps as $chirp)
				<div class="p-6 flex space-x-2">
					<svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
					</svg>
					<div class="flex-1">
						<div class="flex justify-between items-center">
							<div>
								<span class="text-gray-800">{{ $chirp->user->name }}</span>
								<small class="ml-2 text-sm text-gray-600">{{ $chirp->created_at->format('j M Y, g:i a') }}</small>
								@unless ($chirp->created_at->eq($chirp->updated_at))
									<small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
								@endunless
							</div>
							@if ($chirp->user->is(auth()->user()))
								<x-dropdown>
									<x-slot name="trigger">
										<button>
											<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
												<path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
											</svg>
										</button>
									</x-slot>
									<x-slot name="content">
										<x-dropdown-link :href="route('chirps.edit', $chirp)">
											{{ __('Edit') }}
										</x-dropdown-link>
										<form method="POST" action="{{ route('chirps.destroy', $chirp) }}">
											@csrf
											@method('delete')
											<x-dropdown-link :href="route('chirps.destroy', $chirp)" onclick="event.preventDefault(); this.closest('form').submit();">
												{{ __('Delete') }}
											</x-dropdown-link>
										</form>
									</x-slot>
								</x-dropdown>
							@endif
						</div>
						<p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>
						<form id="likeForm-{{ $chirp->id }}" method="POST" action="{{ route('chirps.like', $chirp) }}" class="mt-1">
							@csrf
							<button type="button" onclick="submitForm({{ $chirp->id }})" class="flex items-center">
								<svg id="likeIcon-{{ $chirp->id }}" class="h-5 w-5 text-gray-600 -scale-x-100" fill="{{ in_array($chirp->id, $likedChirps) ? 'red' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<span id="likeCount-{{ $chirp->id }}" class="text-black-500 ml-1">{{ $chirp->likes }}</span>
							</button>
						</form>
						<script>
							function submitForm(chirpId) {
								var form = document.getElementById('likeForm-' + chirpId);
								var icon = document.getElementById('likeIcon-' + chirpId);

								// This increments by 1 versus getting latest value out of DB
								var likeCount = document.getElementById('likeCount-' + chirpId); 
								
								fetch(form.action, {
									method: 'POST',
									body: new FormData(form),
									headers: {
										'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
									}
								})
								.then(response => {
									if (!response.ok) {
										throw new Error('Network error');
									}
									// Toggle SVG icon fill color 
									if (icon.getAttribute('fill') === 'red') {
										icon.setAttribute('fill', 'none');
										likeCount.textContent = parseInt(likeCount.textContent) - 1;	
									} else {
										icon.setAttribute('fill', 'red');
										likeCount.textContent = parseInt(likeCount.textContent) + 1;
									}
								})
								.catch(error => {
									console.error('Error:', error);
								});
							}
						</script>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</x-app-layout>
