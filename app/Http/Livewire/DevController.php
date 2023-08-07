<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Profiles\ProfileIndividual;


use Buglinjo\LaravelWebp\Facades\Webp;

class DevController extends Component
{   
    use WithFileUploads;

    //single
    public $uploadAva;

    //multiple
    public $passportImages = [];




    public $data;


    //SAVE PASSPORT

    public function savePassports(Request $request)
    {   


        dd(11);

        // dd($passportImages);
        // $this->validate([
        //     'passports.*' => 'image|max:10024',
        // ]);
        

        // foreach ($this->passportImages as $passport) {
            
        //     // dd($this->passportImages);

        // }



    }


    public function mount()
    {   


        $this->user = User::find(9);
        $this->profile = ProfileIndividual::where('user_id', 9)->first();

    }



    public function testPost(Request $request)
    {
         $this->validate([         
             'uploadAva' => 'image|max:4024', // 4MB Max
         ]);

        $this->uploadAva->store('storage/avatars/');
        $this->temp_hash = $this->uploadAva->hashName();

        // substr($tempFile,strrpos($tempFile,'/'),strlen($tempFile));
         // $this->tempFile = $this->get_last($tempFile);
         // dd($this->tempFile);

         $this->profile->update([
             'avatar' => $this->temp_hash
         ]);



         Webp::make($this->uploadAva)->save('storage/avatars_webp/');

         // dd($this->profile);

         // $this->data = var_export($this->profile);


    }


    public function checkResponseAvatar()
    {
        // dd($re->all());
    }


    public function sendPassport(Request $request)
    {

        // dd($request->all());

        // dd($request->input('file1'));
    }

    public function render()
    {
        return view('livewire.dev-controller');
    }
}
