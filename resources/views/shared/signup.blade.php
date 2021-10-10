<div class="desktop-only">
	<div class="mt-12 flex justify-center flex items-center text-base text-gray-800 font-semibold">
		🚀 We are launching real soon! Sign up to be invited!
	</div>

	<div class="mt-4 flex justify-center">
		<input type="text" 
			class="p-6 rounded-lg border border-gray-500 text-base email" 
			style="width:{{ $width }};" 
			placeholder="Email" />
	</div>

	<div class="mt-4 flex justify-center">
		<button class="signup bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold rounded-lg shadow-lg px-12 py-6 text-center" style="width:{{ $width }};">
			Get invited when we launch!
		</button>
	</div>

	<script>
		$(document).ready(function() {
			$(".email:first").focus()

			$(".email").on("keyup", function(e) {
				if(e.which == 13) {
					let emails = $(".email")

					for(let i=0; i < emails.length; i++) {
						let email = $(emails[i]).val()
						
						if(email != null && email.length > 0) {
							window.location.href = "/signup/" + btoa(email)
						}
					}
				}
			})

			$("button.signup").on("click", function() {
				let emails = $(".email")

				for(let i=0; i < emails.length; i++) {
					let email = $(emails[i]).val()
					
					if(email != null && email.length > 0) {
						window.location.href = "/signup/" + btoa(email)
					}
				}
			})
		})
	</script>
</div>

<div class="mobile-only">
	<div class="mt-12 flex justify-center flex items-center text-base text-gray-800 font-semibold">
		🚀 We are launching real soon! Sign up to be invited!
	</div>

	<div class="mt-4 flex justify-center">
		<input type="text" 
			class="p-6 rounded-lg border border-gray-500 text-base email-mobile" 
			style="width:{{ $width }};" 
			placeholder="Email" />
	</div>

	<div class="mt-4 flex justify-center">
		<button class="signup-mobile bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold rounded-lg shadow-lg px-12 py-6 text-center" style="width:{{ $width }};">
			Get invited when we launch!
		</button>
	</div>

	<script>
		$(document).ready(function() {
			$(".email-mobile").on("keyup", function(e) {
				if(e.which == 13) {
					let emails = $(".email-mobile")

					for(let i=0; i < emails.length; i++) {
						let email = $(emails[i]).val()
						
						if(email != null && email.length > 0) {
							window.location.href = "/signup/" + btoa(email)
						}
					}
				}
			})

			$("button.signup-mobile").on("click", function() {
				let emails = $(".email-mobile")

				for(let i=0; i < emails.length; i++) {
					let email = $(emails[i]).val()
					
					if(email != null && email.length > 0) {
						window.location.href = "/signup/" + btoa(email)
					}
				}
			})
		})
	</script>
</div>