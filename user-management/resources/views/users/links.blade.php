<div class="flex flex-1">
				<a href="{{ route('users.edit', [auth()->id()]) }}" class="btn btn-primary">Profile Update</a>
				@if (auth()->user()->role == 'administrator')
								<a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
								<a href="{{ route('users.index') }}" class="btn btn-primary">User Listing</a>
				@endif

</div>
