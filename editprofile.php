<script src="https://cdn.tailwindcss.com"></script>

<div class="container mx-auto p-4">
    <h1 class="text-primary font-semibold text-3xl mb-12 mt-4">Edit Profile</h1>
    
    <div class="flex flex-wrap -mx-4">
        <!-- left column -->
        <div class="w-full md:w-1/4 px-4">
            <div class="text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class=" w-56 h-56 rounded-full border border-gray-300" alt="avatar">
                <h6 class="mt-2 mr-32">Upload a different photo...</h6>
                
                <input type="file" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-cyan-150 file:text-black-700 ">
            </div>
        </div>
        
        <!-- edit form column -->
        <div class="w-full md:w-2/4 px-4">
            <div class="bg-blue-100   border-blue-500 text-blue-700 px-4 py-3 mb-4 rounded" role="alert">
                <p class="font-bold">This is an .alert.</p>
                <p class="text-sm">Use this to show important messages to the user.</p>
            </div>
            <h3 class="text-xl mb-4 font-semibold">Personal info</h3>
                
            <form class="space-y-4">
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-right pr-4">Full name</label>
                    <div class="flex-1 flex space-x-4">
                        <input class="w-1/2 border border-gray-300 p-2 rounded-md" type="text" placeholder="First name" value="Diell">
                        <input class="w-1/2 border border-gray-300 p-2 rounded-md" type="text" placeholder="Last name" value="Govori">
                    </div>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-right pr-4">Email</label>
                    <div class="flex-1">
                        <input class="w-full border border-gray-300 p-2 rounded-md" type="text" value="melosshabani@gmail.com" >
                    </div>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-right pr-4">Company</label>
                    <div class="flex-1">
                        <input class="w-full border border-gray-300 p-2 rounded-md" type="text" value="Starlabs" disabled>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button class="bg-cyan-500 text-white font-bold py-2 px-4 rounded mr-2" type="button">Cancel</button>
                    <button class="bg-blue-500  text-white font-bold py-2 px-4 rounded" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
