<div>
    <table class="table table-light table-hover mt-5">
        <tr scope="row">
            <td class="col-3">action_id,#</td>
            <td class="col-3">file</td>
            <td class="col-1"> click button</td>
            <td class="col-5">response</td>
        </tr>
        <tr>
            <td>POST AVA</td>
            <td class="col-7">
                <div class="mb-3 ">
                    <input  class="form-control form-control-sm"  type="file" wire:model="uploadAva">
                        
                </div>
            </td>

            <td>
                <button class="btn-sm btn-light btn" wire:click="testPost()"> UPLOAD </button>
            </td>

            <td class="col-5">

                <div style="
                    max-width: 400px !important;
                    font-size: 12px !important;
                    line-height: 14px;
                    font-family: monospace;
                ">
                    {{  response()->json($profile) }}
                </div>
                
                     

        

             

         </td>
        </tr>

        <tr>
            <td>POST PASSPORT</td>
            <td class="col-7">
                <div class="mb-3 ">

                    <form method="post" enctype="multipart/form-data">
                      <div class="input-group input-group-sm">
                          
                          <input class="form-control form-control-sm" type="text" name="text1" value="name#0">
                          <input class="form-control form-control-sm" type="text" name="text2" value="lastname#1">
                      </div>
                      <hr>
                      {{-- 
                      <div class="input-group input-group-sm mt-2">
                          <p><input class="form-control form-control-sm" type="file" name="file1">
                          <p><input class="form-control form-control-sm" type="file" name="file2">
                          <p><input class="form-control form-control-sm" type="file" name="file3">
                          <p>
                      </div>
                      --}}

                      <form wire:click="savePassports">
                          <input type="file" wire:model="passportImages" multiple>
                       
                          @error('passports.*') <span class="error">{{ $message }}</span> @enderror
                       
                          <button class="btn btn-sm btn-warning text-danger" type="submit">
                          Save Passport files
                          </button>
                      </form>


                        {{-- 
                        <div class="btn btn-sm btn-warning"    wire:click="">Submit
                        </div>
                        --}}

                    </form>


                        
                </div>
            </td>

            <td>
                <button class="btn-sm btn-light btn" wire:click="sendPassport()"> UPLOAD </button>
            </td>

            <td class="col-5">

                <div style="
                    max-width: 400px !important;
                    font-size: 12px !important;
                    line-height: 14px;
                    font-family: monospace;
                ">
                    {{--  response()->json($profile) --}}
                </div>
                
        
         </td>
        </tr>


    </table>

    {{-- CUSTOM DD --}}
    <style type="text/css">
        
        .pre.sf-dump{

            max-width: 400px !important;
        }    

    </style>
    

    
</div>