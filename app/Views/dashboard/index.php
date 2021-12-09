<?= $this->extend('layouts/admin') ?>

<?= $this->section('stylesheet') ?>
 <style>
     .dataTables_filter{
         float: right !important;
     }
 </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Articles List
                            <a href="#" class="btn btn-outline-primary float-right btn-lg" data-toggle="modal" data-target="#addModal">Add Article</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="articleTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Title</th>
                                    <th>Posted On</th>                                   
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>  
                            <tbody>
                                <?php if($articles): ?>
                                    <?php foreach($articles as $row): ?>
                                        <tr id="<?= $row['id']; ?>">
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['title']; ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td>
                                            <a data-id="<?= $row['id']; ?>" class="btn btn-primary btnEdit"><i class="fa fa-edit text-light"></i></a>
                                            <a data-id="<?= $row['id']; ?>" class="btn btn-danger btnDelete"><i class="fa fa-trash text-light"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Article</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addPost" name="addPost" action="<?= base_url('post/store');?>" method="post">
                            <div class="form-group">
                                <label for="txttitle">Title:</label>
                                <input type="text" class="form-control" id="txttitle" placeholder="Enter Title" name="txttitle">
                            </div>
                            <div class="form-group">
                                <label for="txtdescription">Description:</label>
                                <textarea name="txtdescription" id="txtdescription" class="form-control" placeholder="Enter Description" cols="30" rows="10"></textarea>            
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Add</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>

                    <!-- Update User Modal -->
        <div id="updateModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
          
            <!-- User Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModal">Update Article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <form id="updatePost" name="updatePost" action="<?= base_url('post/update');?>" method="post">
                    <input type="hidden" name="hdnArticleId" id="hdnArticleId"/>
                    <div class="form-group">
                        <label for="txttitle">Title:</label>
                        <input type="text" class="form-control" id="txttitle" name="txttitle">
                    </div>
                    <div class="form-group">
                        <label for="txtdescription">Description:</label>
                        <textarea name="txtdescription" id="txtdescription" class="form-control"  cols="30" rows="10"></textarea>            
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </form>
              </div>
            </div>
          </div>
        </div>  
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
 <script>
     $(document).ready(function () {
                     //Add the article  
                $("#addPost").validate({
                 rules: {
                    txttitle: "required",
                    txtdescription: "required",                       
                },
                messages: {

                },
          
                 submitHandler: function(form) {
                  var form_action = $("#addPost").attr("action");
                  $.ajax({
                      data: $('#addPost').serialize(),
                      url: form_action,
                      type: "POST",
                      dataType: 'json',
                      success: function (res) {
                          var article = '<tr id="'+res.data.id+'">';
                          article += '<td>' + res.data.id + '</td>';
                          article += '<td>' + res.data.title + '</td>';
                          article += '<td>' + res.data.description + '</td>';
                          article += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a>&nbsp;&nbsp;<a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a></td>';
                          article += '</tr>';            
                          $('#articleTable tbody').prepend(article);
                          $('#addPost')[0].reset();
                          $('#addModal').modal('hide');
                      },
                      error: function (data) {
                      }
                  });
                }
            });

            //When click edit Post
            $('body').on('click', '.btnEdit', function () {
              var article_id = $(this).attr('data-id');
               $.ajax({
                      url: "<?= base_url('post/update/') ?>" + "/" +article_id,
                      type: "GET",
                      dataType: 'json',
                      success: function (res) {
                          $('#updateModal').modal('show');
                          $('#updatePost #hdnarticleId').val(res.data.id); 
                          $('#updatePost #txttitle').val(res.data.title);
                          $('#updatePost #txtdescription').val(res.data.description);                          
                      },
                      error: function (data) {
                      }
                });
           });
            // Update an article
            $("#updatePost").validate({
                 rules: {
                        txttitle: "required",
                        txtdescription: "required",                        
                    },
                    messages: {
                    },
                 submitHandler: function(form) {
                  var form_action = $("#updatePost").attr("action");
                  $.ajax({
                      data: $('#updatePost').serialize(),
                      url: form_action,
                      type: "POST",
                      dataType: 'json',
                      success: function (res) {
                          var article = '<td>' + res.data.id + '</td>';
                          article += '<td>' + res.data.title + '</td>';
                          article += '<td>' + res.data.created_at + '</td>';                         
                          article += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a>&nbsp;&nbsp;<a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a></td>';
                          $('#articleTable tbody #'+ res.data.id).html(article);
                          $('#updatePost')[0].reset();
                          $('#updateModal').modal('hide');
                      },
                      error: function (data) {
                      }
                  });
                }
            });
            
            //delete student
            $('body').on('click', '.btnDelete', function () {
              var article_id = $(this).attr('data-id');
              $.get("<?=base_url('post/delete/') ?>" + "/" +article_id, function (data) {
                  $('#articleTable tbody #'+ article_id).remove();
              })
           });  
           
     });
 </script>

<script>
$(document).ready(function() {
    $('#articleTable').DataTable();
} );
</script>
<?= $this->endSection() ?>