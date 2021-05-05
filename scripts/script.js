//Logics for collapsible button
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        //Clear input fields on button click
        document.getElementById("new_pass").value = "";
        document.getElementById("new_email").value = "";
        document.getElementById("new_number").value = "";
        //Display next button sibling if button clicked, hide if clicked again
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
            content.style.display = "none";
        } 
        else {
            content.style.display = "block";
        }
    });
}  
//Function to validate all registration input
function validate_registration() {
    //Read values of input fields
    var username = document.getElementById("username").value;
    var pwd = document.getElementById("pwd").value;
    var fname = document.getElementById("fname").value;
    var idcode = document.getElementById("idcode").value;
    var email = document.getElementById("email").value;
    var pnumber = document.getElementById("pnumber").value;
    //Validate username
    if (username.match(/^\w{5,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your username is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    //Validate password
    else if (pwd.match(/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your password is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    //Validate full name
    else if (fname.match(/^[a-zA-Z'\s-]+$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your full name is invalid",   
            icon: "error",
            button: "OK"});
        return false;;
    }
    //Validate ID code
    else if (idcode.match(/^[3-6]([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])[0-9]{4}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your ID-code is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    //Validate email
    else if (email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your email is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    //Validate phone number
    else if (pnumber.match(/^[0-9+-]{1,7}[0-9]{6,9}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your phone number is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
}
//Function to validate all login input

function validate_login() {
    //Read values of input fields
    var username = document.getElementById("username").value;
    var pwd = document.getElementById("pwd").value;
    //Validate username

    if(username.length === 0 || username === ""){
        swal({ title: "Username is empty",   
            text: "Your username is empty",   
            icon: "error",
            button: "OK"});
        return false;
    }
    
    else if (username.match(/^\w{5,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your username is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }

    else if (pwd.length === 0 || pwd === ""){
        swal({ title: "password is empty",   
            text: "Your password is empty",   
            icon: "error",
            button: "OK"});
        return false;
    }
    
    //Validate password
    else if (pwd.match(/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your password is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
}

//Function to validate account details input
function validate_details() {
    //Read values of input fields
    var new_pass = document.getElementById("new_pass").value;
    var new_email = document.getElementById("new_email").value;
    var new_number = document.getElementById("new_number").value;
    //If more than one input fields are used, show alert
    if ((new_pass.length != 0 && new_email.length != 0 && new_number.length != 0) || (new_pass.length != 0 && new_email.length != 0) || (new_email.length != 0 && new_number.length != 0) || (new_pass.length != 0 && new_number.length != 0)) {
        swal({ title: "More than one detail chosen",   
            text: "You can only change one account detail at a time. Please, clear other input fields!",   
            icon: "error",
            button: "OK"});
        //Clear input fields
        document.getElementById("new_pass").value = "";
        document.getElementById("new_email").value = "";
        document.getElementById("new_number").value = "";    
        return false;  
    }
    //Validate password if present
    else if (new_pass.length != 0 && new_pass.match(/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your password is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    //Validate email if present
    else if (new_email.length != 0 && new_email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your email is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    //Validate phone number if present
    else if (new_number.length != 0 && new_number.match(/^[0-9+-]{1,7}[0-9]{6,9}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your phone number is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
}

function validate_checkout() {
    //Initialize variables for services
    var plate_traffic, power_traffic, plate_casco, power_casco, age, income, area;
    //Get values of variables from input fields, assign blank if these services are not used
    if (document.getElementById("plate_traffic")) {
        plate_traffic = document.getElementById("plate_traffic").value;
        power_traffic = document.getElementById("power_traffic").value;   
    }
    else {
        plate_traffic = power_traffic = "";
    }
    if (document.getElementById("plate_casco")) {
        plate_casco = document.getElementById("plate_casco").value;
        power_casco = document.getElementById("power_casco").value;
    }
    else {
        plate_casco = power_casco = "";
    }
    if (document.getElementById("age")) {
        age = document.getElementById("age").value;
        income = document.getElementById("income").value;
    }
    else {
        age = income = "";
    }
    if (document.getElementById("area")) {
        area = document.getElementById("area").value;
    }
    else {
        area = "";
    }
    //Validate traffic plate and power if present
    if (plate_traffic.length != 0 && plate_traffic.match(/^[0-9]{3}[A-Z]{3}$/) == null) {
        swal({ title: "Invalid input",   
            text: "License plate for traffic insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (power_traffic.length != 0 && (power_traffic.match(/^[0-9]{1,3}$/) == null || power_traffic < 40)) {
        swal({ title: "Invalid input",   
            text: "Power for traffic insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    //Validate casco plate and power if present
    else if (plate_casco.length != 0 && plate_casco.match(/^[0-9]{3}[A-Z]{3}$/) == null) {
        swal({ title: "Invalid input",   
            text: "License plate for casco insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (power_casco.length != 0 && (power_casco.match(/^[0-9]{1,3}$/) == null || power_traffic < 40)) {
        swal({ title: "Invalid input",   
            text: "Power for casco insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    //Validate age and income if present
    else if (age.length != 0 && (age.match(/^[0-9]{1,3}$/) == null || age < 18 || age > 120)) {
        swal({ title: "Invalid input",   
            text: "Age is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (income.length != 0 && (age.match(/^[0-9]{1,}$/) == null || income < 0 )) {
        swal({ title: "Invalid input",   
            text: "Income is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    //Validate home area if present
    else if (area.length != 0 && (area.match(/^[0-9]{1,3}$/) == null || area < 1 || area > 999 )) {
        swal({ title: "Invalid input",   
            text: "Area is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
}

