// RESET PASSWORD

// Reset password button
const passwordForm = document.getElementById('pwd_form');
const passwordErr = document.getElementById('pwd_error');
const passwordSucc = document.getElementById('pwd_success');

passwordForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Get the form data
    let formData = new FormData(this);

    // Send the form data
    try {
        const response = await fetch('api/password_reset.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            passwordErr.style.display = 'block';
            passwordErr.innerHTML = 'Virhe salasanan päivityksessä, yritä uudelleen.';
            throw new Error('Virhe salasanan päivityksessä, yritä uudelleen.');
        }

        // Get the response data
        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing JSON:', error);
            return;
        }

        // Display the error message
        if (!data.success) {
            passwordErr.style.display = 'block';
            passwordErr.innerHTML = data.error;
            setTimeout(() => {
                passwordErr.innerHTML = '';
                passwordErr.style.display = 'none';
            }, 3000);
            return;
        }

        // Update the password
        if(data.success) {
            passwordSucc.style.display = 'block';
            passwordSucc.innerHTML = data.message;
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }

    // Clear the input
    document.getElementById('password_form').reset();
});