document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("register-form");

    if (!form) {
        return;
    }


    form.addEventListener("submit", function (event) {

        event.preventDefault();


        // Get form values
        const name = document
            .getElementById("customer_name")
            .value
            .trim();

        const email = document
            .getElementById("customer_email")
            .value
            .trim();

        const password = document
            .getElementById("customer_pass")
            .value;

        const country = document
            .getElementById("customer_country")
            .value
            .trim();

        const city = document
            .getElementById("customer_city")
            .value
            .trim();

        const contact = document
            .getElementById("customer_contact")
            .value
            .trim();


        // Validation patterns
        const emailRegex =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        const phoneRegex =
            /^[0-9+\-\s]{7,15}$/;


        // Clear previous message
        showMessage("");


        // Name validation
        if (name === "") {

            showMessage("Please enter your full name.");

            return;
        }


        // Email validation
        if (!emailRegex.test(email)) {

            showMessage("Please enter a valid email address.");

            return;
        }


        // Password validation
        if (password.length < 8) {

            showMessage(
                "Password must be at least 8 characters."
            );

            return;
        }


        // Country validation
        if (country === "") {

            showMessage("Please enter your country.");

            return;
        }


        // City validation
        if (city === "") {

            showMessage("Please enter your city.");

            return;
        }


        // Contact validation
        if (!phoneRegex.test(contact)) {

            showMessage(
                "Please enter a valid contact number."
            );

            return;
        }


        // Prepare form data
        const formData = new FormData(form);


        // Send data to PHP
        fetch("../actions/register_action.php", {

            method: "POST",

            body: formData

        })

        .then(function (response) {

            return response.text();

        })

        .then(function (text) {

            console.log("PHP RESPONSE:");
            console.log(text);


            let data;

            try {

                data = JSON.parse(text);

            } catch (error) {

                console.error(
                    "PHP did not return valid JSON:"
                );

                console.error(text);

                showMessage(
                    "PHP Error: " + text
                );

                return;
            }


            if (data.success) {

                showMessage(data.message);

                form.reset();

            } else {

                showMessage(data.message);
            }

        })

        .catch(function (error) {

            console.error(
                "Registration error:",
                error
            );

            showMessage(
                "Something went wrong. Please try again."
            );
        });

    });


    function showMessage(message) {

        const messageBox =
            document.getElementById("register-message");


        if (messageBox) {

            messageBox.textContent = message;
        }
    }

});