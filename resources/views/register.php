<div class="min-h-screen bg-gray-100 p-0 sm:p-12">
    <div class="mx-auto max-w-md px-6 py-12 bg-white border-0 shadow-lg sm:rounded-3xl">
        <h1 class="text-2xl font-bold mb-8">Create an Account</h1>
        <form id="form" novalidate method="post">
            <div class="relative z-0 w-full mb-5">
                <input
                        type="text"
                        name="name"
                        placeholder=" "
                        required
                        class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                />
                <label for="name" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter name</label>
                <span class="text-sm text-red-600 hidden" id="error">Name is required</span>
            </div>

            <div class="relative z-0 w-full mb-5">
                <input
                        type="email"
                        name="email"
                        placeholder=" "
                        class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                />
                <label for="email" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter email
                    address</label>
                <span class="text-sm text-red-600 hidden" id="error">Email address is required</span>
            </div>

            <div class="relative z-0 w-full mb-5">
                <input
                        type="password"
                        name="password"
                        placeholder=" "
                        class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                />
                <label for="password" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter
                    password</label>
                <span class="text-sm text-red-600 hidden" id="error">Password is required</span>
            </div>

            <div class="relative z-0 w-full mb-5">
                <input
                        type="password"
                        name="confirm-password"
                        placeholder=" "
                        class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                />
                <label for="confirm-password" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Confirm
                    Password</label>
                <span class="text-sm text-red-600 hidden" id="error">Confirm password is required</span>
            </div>


            <div class="relative z-0 w-full mb-5">
                <div class="relative z-0 w-full mb-5">
                    <input
                            type="text"
                            name="date"
                            placeholder=" "
                            onclick="this.setAttribute('type', 'date');"
                            class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                    />
                    <label for="date" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Date</label>
                    <span class="text-sm text-red-600 hidden" id="error">Date is required</span>
                </div>
            </div>
            <button
                    id="submitBtn"
                    class="w-full px-6 py-3 mt-3 text-lg text-white transition-all duration-150 ease-linear rounded-lg shadow outline-none bg-gray-500 hover:bg-gray-800 hover:shadow-lg focus:outline-none"
            >
                Toggle Error
            </button>

        </form>
    </div>
</div>