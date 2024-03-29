@extends('layouts.app')

@section('project_title',"CRUD")

@section('content')

<div class="row">
    <div class="col-md-8">
        <h4>Manage Crud</h4>
        <hr>
        <table class="table table-bordered text-center">
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <tbody id="tbody">
                {{-- <tr>
                    <td>1</td>
                    <td>Name</td>
                    <td><img src="" alt="" width="100px"></td>
                    <td>
                        <a data-bs-toggle="modal" data-bs-target="#viewModal" class="btn btn-sm btn-success" id="viewRow">View</a>
                        <a data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-sm btn-info" id="editRow">Edit</a>
                        <a id="deleteRow" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr> --}}
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h4>Add New Data</h4>
        <form action="" id="addDataForm">
            <div class="my-2">
                <label for="">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name">
                <span class="text-danger" id="nameErr"></span>
            </div>
            <div class="my-2">
                <label for="">Image</label>
                <input type="file" class="form-control" id="image">
                <span class="text-danger" id="imgErr"></span>
            </div>
            <div class="my-2">
                <button class="btn btn-sm btn-block btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- View  Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">View Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewTableData">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit  Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">edit Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
@stop

@push('script')
    <script>

        const getAllData = async () => {
            let url = `${base_url}/crud/get-data`;

            try{
                const response = await axios.get(url);

                table_data_row(response.data.data)
            }catch(err){
                log(err)
            }

        }

        const table_data_row = (items) => {
            let loop = items.map((item,index) => {
                return ` <tr>
                    <td>${++ index}</td>
                    <td>${item.name}</td>
                    <td><img src="{{ asset('${item.image}') }}" alt="" width="100px"></td>
                    <td>
                        <a data-bs-toggle="modal" data-bs-target="#viewModal" class="btn btn-sm btn-success" id="viewRow" data-id="${item.id}">View</a>

                        <a data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-sm btn-info" id="editRow" data-id="${item.id}">Edit</a>

                        <a id="deleteRow" class="btn btn-sm btn-danger" data-id="${item.id}">Delete</a>
                    </td>
                </tr>`
            });

            loop = loop.join("");
            // log(loop);

            let tbody = $$('#tbody');

            tbody.innerHTML = loop;
        }

        getAllData();

        // View

        document.addEventListener("click", async (e) => {

            if(e.target.matches('a[href],a[href] *')){
                e.preventDefault();
            }

            const row = e.target.closest("#viewRow");
            if(row){
                let id = row.getAttribute('data-id');

                let url = `${base_url}/crud/show/${id}`;

                const response = await axios.get(url);

                let html = ` <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <td>${response.data.name}</td>
            </tr>
            <tr>
                <th>Image</th>
                <td><img src="{{ asset('${response.data.image}') }}" width="200px" alt=""></td>
            </tr>
          </table>`;

          let viewTableData = $$('#viewTableData');
          viewTableData.innerHTML = html;
                // log(response);
            }
        });


        // Delete
        document.addEventListener("click", (e) => {

    if(e.target.matches('a[href],a[href] *')){
        e.preventDefault();
    }

    const row = e.target.closest("#deleteRow");
    if(row){
        let id = row.getAttribute('data-id');

        let url = `${base_url}/crud/delete/${id}`;
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {

     axios.post(url).then((res)=>{

         getAllData();
     })
                swalWithBootstrapButtons.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your imaginary file is safe :)',
                'error'
                )
            }
            })
    }


        // Store

        let addDataForm = $$("#addDataForm");

        addDataForm.addEventListener("submit", async function  (e){
            e.preventDefault();

            let name = $$("#name");
            let nameErr = $$("#nameErr");
            let image = $$("#image");
            let imgErr = $$("#imgErr");

            nameErr.innerText = "";
            imgErr.innerText = "";

            if(name.value == ""){
                nameErr.innerText = "Field Must not be empty";
                return false;
            }
            if(image.value == ""){
                imgErr.innerText = "Field Must not be empty";
                return false;
            }


            let data = new FormData();

            // log(image.files[0])
            data.append("name",name.value);
            data.append("image",image.files[0]);

            try{
                let url = `${base_url}/crud/store`;
                const res = await axios.post(url,data);
                Swal.fire({
  icon: 'success',
  title: 'Success',
  text: 'Data save successfully!',
})
                getAllData();
                name.value = "";
                image.value = null;
            }catch(err){
                if(err.response.data.errors.name){
                    nameErr.innerText = err.response.data.errors.name[0];
                }
                if(err.response.data.errors.image){
                    imgErr.innerText = err.response.data.errors.image[0];
                }
            }
        })

});



    </script>
@endpush
