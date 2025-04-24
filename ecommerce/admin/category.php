<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShreeSaiEnterprises - Admin</title>
</head>

<body>
    <div class="pcoded-inner-content pt-0">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                </div>
                                <div class="card-block">
                                    <div class="row">

                                        <div class="col-sm-6 col-md-4 col-lg-4">
                                            <h4 class="sub-title">Category</h4>
                                            <div>
                                                <asp:TextBox id="txtName" runat="server" CssClass="form-control"
                                                    placeholder="Enter Category Name" required></asp:TextBox>
                                                <asp:HiddenField ID="hdnId" runat="server" Value="0" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Category Image</label>
                                            <div>
                                                <asp:FileUplode ID="fuCategoryImage" runat="server"
                                                    CssClass="form-control" onchange="ImagePreview(this);" />
                                            </div>
                                        </div>
                                        <div class="form-check pl-4">
                                            <asp:CheckBox ID="cbIsActive" runat="server" Text="&nbsp; IsActive"
                                                CssClass="form-check-input" />
                                        </div>
                                        <div class="pb-5">
                                            <asp:Button ID="btnAddOrUpdate" runat="server" Text="Add" CssClass="btn btn-primary" 
                                              onclick="btnAddOrUpdate_Click"/>
                                            &nbsp;
                                             <asp:Button ID="btnClear" runat="server" Text="Clear" CssClass="btn btn-primary"
                                                CausesValidation="false" />
                                        </div>
                                        <div>
                                            <asp:Button ID="imgCategory" runat="server" CssClass="img-thumnail" />
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>