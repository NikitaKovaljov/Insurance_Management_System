//Logics for collapsible button
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        //Clear input fields on button click
        document.getElementById("newPass").value = "";
        document.getElementById("newEmail").value = "";
        document.getElementById("newNumber").value = "";
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

function validateRegistration() {
    var username = document.getElementById("username").value;
    var pwd = document.getElementById("pwd").value;
    var fname = document.getElementById("fname").value;
    var idcode = document.getElementById("idcode").value;
    var email = document.getElementById("email").value;
    var pnumber = document.getElementById("pnumber").value;

    if (username.match(/^\w{5,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your username is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (pwd.match(/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your password is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (fname.match(/^[a-zA-Z'\s-]+$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your full name is invalid",   
            icon: "error",
            button: "OK"});
        return false;;
    }
    else if (idcode.match(/^[3-6]([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])[0-9]{4}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your ID-code is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your email is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (pnumber.match(/^[0-9+-]{1,7}[0-9]{6,9}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your phone number is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
}

function validateLogin() {
    var username = document.getElementById("username").value;
    var pwd = document.getElementById("pwd").value;
    if (username.match(/^\w{5,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your username is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (pwd.match(/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your password is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
}

function validateDetails() {
    var newPass = document.getElementById("newPass").value;
    var newEmail = document.getElementById("newEmail").value;
    var newNumber = document.getElementById("newNumber").value;

    if ((newPass.length != 0 && newEmail.length != 0 && newNumber.length != 0) || (newPass.length != 0 && newEmail.length != 0) || (newEmail.length != 0 && newNumber.length != 0) || (newPass.length != 0 && newNumber.length != 0)) {
            swal({ title: "More than one detail chosen",   
            text: "You can only change one account detail at a time",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (newPass.length != 0 && newPass.match(/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your password is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (newEmail.length != 0 && newEmail.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your email is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
    else if (newNumber.length != 0 && newNumber.match(/^[0-9+-]{1,7}[0-9]{6,9}$/) == null) {
        swal({ title: "Invalid input",   
            text: "Your phone number is invalid",   
            icon: "error",
            button: "OK"});
        return false;
    }
}

function validateCheckout() {
    var plateTraffic, powerTraffic, plateCasco, powerCasco, age, income, area;
    if (document.getElementById("plate_traffic")) {
        plateTraffic = document.getElementById("plate_traffic").value;
        powerTraffic = document.getElementById("power_traffic").value;   
    }
    else {
        plateTraffic = powerTraffic = "";
    }
    if (document.getElementById("plate_casco")) {
        plateCasco = document.getElementById("plate_casco").value;
        powerCasco = document.getElementById("power_casco").value;
    }
    else {
        plateCasco = powerCasco = "";
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

    if (plateTraffic.length != 0 && plateTraffic.match(/^[0-9]{3}[A-Z]{3}$/) == null) {
        swal({ title: "Invalid input",   
            text: "License plate for traffic insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (powerTraffic.length != 0 && (powerTraffic.match(/^[0-9]{1,3}$/) == null || powerTraffic < 40)) {
        swal({ title: "Invalid input",   
            text: "Power for traffic insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (plateCasco.length != 0 && plateCasco.match(/^[0-9]{3}[A-Z]{3}$/) == null) {
        swal({ title: "Invalid input",   
            text: "License plate for casco insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
    else if (powerCasco.length != 0 && (powerCasco.match(/^[0-9]{1,3}$/) == null || powerTraffic < 40)) {
        swal({ title: "Invalid input",   
            text: "Power for casco insurance is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
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
    else if (area.length != 0 && (area.match(/^[0-9]{1,3}$/) == null || area < 1 || area > 999 )) {
        swal({ title: "Invalid input",   
            text: "Area is invalid",   
            icon: "error",
            button: "OK"});
        return false;  
    }
}
