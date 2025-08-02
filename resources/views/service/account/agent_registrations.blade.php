@extends('layouts.app')
@section('title', "BC Agent Registration")
@section('pagetitle', "BC Agent Registration")

@section('content')
    <div class="content">
        <h3>BC Agent Registration</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Agent Details</h5>
                    </div>

                    <form action="{{ route('service.register.agent') }}" method="post" id="bcAgentForm" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <!-- Personal Information -->
                            <h6 class="form-section-title">Personal Information</h6>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bcagentname" placeholder="Enter first name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" name="middlename" placeholder="Enter middle name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="lastname" placeholder="Enter last name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Pan Card <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="panNo" placeholder="Enter PAN number" maxlength="10">
                                    <small class="form-text text-muted">Format: ABCDE1234F</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="companyname" placeholder="Enter company name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Date of Birth <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control dobdate" name="dob" placeholder="DD/MM/YYYY" readonly>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <h6 class="form-section-title mt-4">Contact Information</h6>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mobilenumber" placeholder="Enter 10 digit mobile number">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Alternate Number</label>
                                    <input type="text" class="form-control" name="alternatenumber" placeholder="Enter alternate number">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Telephone</label>
                                    <input type="text" class="form-control" name="telephone" placeholder="Enter telephone number">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email ID <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="emailid" placeholder="Enter email address">
                            </div>

                            <!-- Residential Address -->
                            <h6 class="form-section-title mt-4">Residential Address</h6>
                            <div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Enter complete address"></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="statename" data-placeholder="Select state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{$state->state}}">{{$state->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                              
                                <div class="form-group col-md-3">
                                    <label>City <span class="text-danger">*</span></label>
                                    <input class="form-control" name="cityname" placeholder="Enter city name">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>District <span class="text-danger">*</span></label>
                                    <input class="form-control" name="district" placeholder="Enter district name">
                                </div>
                              

                                <div class="form-group col-md-3">
                                    <label>Area <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="area" placeholder="Enter area name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>PIN Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pincode" placeholder="Enter 6 digit PIN code">
                            </div>

                            <!-- Shop Address -->
                            <h6 class="form-section-title mt-4">Shop Address</h6>
                            <div class="form-group">
                                <label>Shop Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="shopaddress" rows="3" placeholder="Enter shop address"></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Shop State <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="shopstate" data-placeholder="Select shop state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{$state->state}}">{{$state->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="form-group col-md-3">
                                    <label>Shop City <span class="text-danger">*</span></label>
                                    <input class="form-control" name="shopcity" placeholder="Enter shop city">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Shop District <span class="text-danger">*</span></label>
                                    <input class="form-control" name="shopdistrict" placeholder="Enter shop district">
                                </div>
                        

                                <div class="form-group col-md-3">
                                    <label>Shop Area <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shoparea" placeholder="Enter shop area">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Shop PIN Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="shoppincode" placeholder="Enter 6 digit shop PIN code">
                            </div>

                            <!-- Products -->
                            <h6 class="form-section-title mt-4">Product Details</h6>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="dmt" value="1" class="styled"> DMT
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="aeps" value="1" class="styled"> AEPS
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cardpin" value="1" class="styled"> Card PIN
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="accountopen" value="1" class="styled"> Account Open
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Terminal Details -->
                            <div id="terminalDetails" style="display: none;">
                                <h6 class="form-section-title mt-4">Terminal Details</h6>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Terminal Serial Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tposserialno" placeholder="Enter terminal serial number">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Terminal Email</label>
                                        <input type="email" class="form-control" name="temail" placeholder="Enter terminal email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Terminal Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="taddress" rows="2" placeholder="Enter terminal address"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Terminal Address Line 2</label>
                                    <textarea class="form-control" name="taddress1" rows="2" placeholder="Enter additional address details"></textarea>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Terminal State <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="tstate" data-placeholder="Select terminal state">
                                            <option value="">Select State</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Terminal City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tcity" placeholder="Enter terminal city">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Terminal PIN Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tpincode" placeholder="Enter 6 digit terminal PIN code">
                                    </div>
                                </div>
                            </div>

                            <!-- Agent Type -->
                            <h6 class="form-section-title mt-4">Agent Type</h6>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Agent Type <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="agenttype" data-placeholder="Select agent type">
                                        <option value="1">Normal Agent</option>
                                        <option value="2">Direct Agent</option>
                                    </select>
                                </div>
                               
                                <div class="form-group col-md-6">
                                    <label>BC AGENT ID<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bc_agent_id" placeholder="Enter BC AGENT ID">
                                </div>
                                 <div class="form-group col-md-6">
                                    <!--<label>BC AGENT ID<span class="text-danger">*</span></label>-->
                                    <input type="hidden" class="form-control" name="agentbcid" value = "3653" placeholder="Enter BC AGENT ID">
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer text-center">
                            <button type="submit" class="btn bg-teal-800 btn-labeled btn-lg">
                                <b><i class="icon-paperplane"></i></b> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!--<div class="col-md-4">-->
            <!--    <div class="panel panel-default">-->
            <!--        <div class="panel-heading">-->
            <!--            <h5 class="panel-title">Instructions</h5>-->
            <!--        </div>-->
            <!--        <div class="panel-body">-->
            <!--            <ul class="list-unstyled">-->
            <!--                <li><i class="icon-circle-right2 text-primary"></i> All fields marked with * are mandatory</li>-->
            <!--                <li><i class="icon-circle-right2 text-primary"></i> Mobile number should be 10 digits</li>-->
            <!--                <li><i class="icon-circle-right2 text-primary"></i> Terminal details are required for AEPS/Card PIN</li>-->
            <!--                <li><i class="icon-circle-right2 text-primary"></i> PAN format: ABCDE1234F</li>-->
            <!--                <li><i class="icon-circle-right2 text-primary"></i> All PIN codes must be 6 digits</li>-->
            <!--            </ul>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>
@endsection

@push('css')
<style>
.form-section-title {
    margin-top: 20px;
    margin-bottom: 15px;
    padding-bottom: 5px;
    border-bottom: 1px solid #ddd;
    color: #333;
    font-weight: 600;
}
.error-message {
    color: #d9534f;
    margin-top: 5px;
    font-size: 12px;
}
.panel-footer {
    padding: 20px;
    border-top: 1px solid #ddd;
}
</style>
@endpush

@push('script')
<script type="text/javascript">
$(document).ready(function() {
    // Initialize datepicker
    $('.dobdate').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
    });

    // Initialize select2
    $('.select2').select2();

    // Handle Terminal Details visibility
    function toggleTerminalDetails() {
        var isVisible = $('input[name="aeps"]').is(':checked') || $('input[name="cardpin"]').is(':checked');
        $('#terminalDetails').toggle(isVisible);
        
        // Toggle required attributes
        var requiredFields = ['tposserialno', 'taddress', 'tstate', 'tcity', 'tpincode'];
        $('#terminalDetails').find('input, select, textarea').each(function() {
            if (requiredFields.includes($(this).attr('name'))) {
                $(this).prop('required', isVisible);
            }
        });
    }

    $('input[name="aeps"], input[name="cardpin"]').on('change', toggleTerminalDetails);
    toggleTerminalDetails();

    // Form Validation
    $("#bcAgentForm").validate({
        rules: {
            bcagentname: {
                required: true,
                minlength: 2
            },
            lastname: {
                required: true,
                minlength: 2
            },
            panNo: {
                required: true,
                pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/
            },
            companyname: {
                required: true
            },
            dob: {
                required: true,
                date: true
            },
            mobilenumber: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            alternatenumber: {
                number: true,
                minlength: 10,
                maxlength: 10
            },
            telephone: {
                number: true,
                maxlength: 14
            },
            emailid: {
                required: true,
                email: true
            },
            pincode: {
                required: true,
                number: true,
                minlength: 6,
                maxlength: 6
            },
            shoppincode: {
                required: true,
                number: true,
                minlength: 6,
                maxlength: 6
            },
            agentbcid: {
                required: true
            }
        },
        messages: {
            bcagentname: {
                required: "Please enter first name",
                minlength: "First name must be at least 2 characters"
            },
            lastname: {
                required: "Please enter last name",
                minlength: "Last name must be at least 2 characters"
            },
            panNo: {
                required: "Please enter PAN number",
                pattern: "Please enter valid PAN number"
            },
            companyname: {
                required: "Please enter company name"
            },
            dob: {
                required: "Please enter date of birth",
                date: "Please enter valid date"
            },
            mobilenumber: {
                required: "Please enter mobile number",
                number: "Please enter valid mobile number",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number must be 10 digits"
            },
            emailid: {
                required: "Please enter email address",
                email: "Please enter valid email address"
            },
            pincode: {
                required: "Please enter PIN code",
                number: "Please enter valid PIN code",
                minlength: "PIN code must be 6 digits",
                maxlength: "PIN code must be 6 digits"
            },
            shoppincode: {
                required: "Please enter shop PIN code",
                number: "Please enter valid PIN code",
                minlength: "PIN code must be 6 digits",
                maxlength: "PIN code must be 6 digits"
            },
         
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.hasClass("select2-hidden-accessible")) {
                error.insertAfter(element.next(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        // submitHandler: function(form) {
        //     var $form = $(form);
        //     $form.find('span.text-danger').remove();
            
        //     $form.ajaxSubmit({
        //         dataType: 'json',
        //         beforeSubmit: function() {
        //             $form.find('button:submit').button('loading');
        //         },
        //         complete: function() {
        //             $form.find('button:submit').button('reset');
        //         },
        //         success: function(response) {
        //             alert(response);
        //             if (response.status === "success") {
        //                 form.reset();
        //                 $('.select2').val('').trigger('change');
        //                 notify("BC Agent Successfully Registered", 'success');
                        
        //                 // Optional: Redirect after success
        //                 // setTimeout(function() {
        //                 //     window.location.href = '/dashboard';
        //                 // }, 2000);
        //             } else {
        //                 notify(response.message || "Registration failed", 'error');
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             var errors = xhr.responseJSON;
        //             if (errors) {
        //                 // Handle validation errors from server
        //                 $.each(errors.errors, function(field, messages) {
        //                     var input = $('[name="' + field + '"]');
        //                     input.after('<p class="text-danger">' + messages[0] + '</p>');
        //                 });
        //                 notify("Please correct the errors", 'error');
        //             } else {
        //                 notify("An error occurred while processing your request", 'error');
        //             }
        //         }
        //     });
        // }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("bcAgentForm");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // Remove previous validation messages
        document.querySelectorAll("span.text-danger").forEach(function (el) {
            el.remove();
        });

        var formData = new FormData(form);
        var submitButton = form.querySelector("button[type='submit']");

        // Disable submit button while processing
        submitButton.disabled = true;
        submitButton.innerText = "Loading...";

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest" // Helps Laravel recognize it as an AJAX request
            }
        })
        .then(response => response.text()) // Get raw response
        .then(text => {
            console.log("Raw Response:", text); // Debugging

            try {
                // Try parsing as JSON first
                var jsonResponse = JSON.parse(text);
                if (jsonResponse.raw_response) {
                    return parseXMLResponse(jsonResponse.raw_response); // Extract data from XML inside JSON
                }
                return jsonResponse;
            } catch (error) {
                // If JSON parsing fails, parse as XML
                return parseXMLResponse(text);
            }
        })
        .then(response => {
            console.log("Parsed Response:", response); // Debugging

            if (!response.statusText) {
                if (response.status === 1 || response.statuscode === "TXN") {
                    form.reset();
                    notify("BC Agent Successfully Registered", "success");
                    // Open a link if the response has a URL
                    if (response.message && response.statuscode === "TXN") {
                        window.open(response.message);
                    }
                } else if (response.status === 0 || response.statuscode === "TXF") {
                    showAlert("Oops!", response.description || "Transaction failed", "red");
                } else {
                    showAlert("Oops!", response.description || "An error occurred", "red");
                }
            } else {
                showError(response, form);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Oops!", "An error occurred while processing your request", "red");
        })
        .finally(() => {
            // Enable the button again
            submitButton.disabled = false;
            submitButton.innerText = "Submit";
        });
    });

    // Function to parse XML response
    function parseXMLResponse(xmlString) {
        var parser = new DOMParser();
        var xmlDoc = parser.parseFromString(xmlString, "text/xml");

        var status = xmlDoc.getElementsByTagName("status")[0]?.textContent || "N/A";
        var description = xmlDoc.getElementsByTagName("description")[0]?.textContent || "Invalid response from server";

        return { status: parseInt(status), description: description };
    }
    
    // Show success/error notification
    function notify(message, type) {
        $.alert({
            title: type === "success" ? "Success!" : "Oops!",
            content: message,
            type: type === "success" ? "green" : "red"
        });
    }

    // Show error messages (similar to SYSTEM.SHOWERROR)
    function showError(data, form) {
        $.alert({
            title: "Error!",
            content: data.description || "Something went wrong!",
            type: "red"
        });

        if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                var input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    var errorElement = document.createElement("p");
                    errorElement.className = "text-danger";
                    errorElement.innerText = data.errors[field][0];
                    input.parentNode.appendChild(errorElement);
                }
            });
        }
    }
    
    // Helper function for alert popups
    function showAlert(title, content, type) {
        $.alert({
            title: title,
            content: content,
            type: type
        });
    }
});


</script>
@endpush
