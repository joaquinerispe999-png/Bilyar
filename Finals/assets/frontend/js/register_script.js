let emailAvailable = false;
let usernameAvailable = false;

async function nextStep(current)
{
    const currentStep =
        document.getElementById(`step${current}`);

    const inputs =
        currentStep.querySelectorAll('input');

    let valid = true;

    const errorBox =
        document.getElementById(`step${current}Error`);

    if(errorBox)
    {
        errorBox.innerHTML = '';
    }

    inputs.forEach(input =>
    {
        if(input.value.trim() === '')
        {
            valid = false;
        }
    });

    if(!valid)
    {
        if(errorBox)
        {
            errorBox.innerHTML =
                'Please fill out all fields.';
        }

        return;
    }

    if(current === 1)
    {
        await checkUsername();

        if(!usernameAvailable)
        {
            if(errorBox)
            {
                errorBox.innerHTML =
                    'Username is already taken.';
            }

            return;
        }
    }

    if(current === 2)
    {
        await checkEmail();

        if(!emailAvailable)
        {
            if(errorBox)
            {
                errorBox.innerHTML =
                    'Email is already registered.';
            }

            return;
        }
    }

    currentStep.classList.remove('active');

    document
        .getElementById(`step${current + 1}`)
        .classList.add('active');
}

function prevStep(current)
{
    document
        .getElementById(`step${current}`)
        .classList.remove('active');

    document
        .getElementById(`step${current - 1}`)
        .classList.add('active');
}

document.addEventListener('DOMContentLoaded', () =>
{
    const password =
        document.getElementById('password');

    const checker =
        document.getElementById('passwordChecker');

    if(password)
    {
        password.addEventListener('focus', () =>
        {
            checker.classList.add('show');
        });

        password.addEventListener(
            'input',
            checkPasswordStrength
        );
    }
});

const usernameInput =
    document.querySelector(
        'input[name="username"]'
    );

if(usernameInput)
{
    usernameInput.addEventListener(
        'blur',
        checkUsername
    );
}

async function checkUsername()
{
    const username =
        document.querySelector(
            'input[name="username"]'
        ).value.trim();

    if(username === '')
    {
        return;
    }

    const formData =
        new FormData();

    formData.append(
        'type',
        'username'
    );

    formData.append(
        'value',
        username
    );

    const response =
        await fetch(
            'process/check_availability.php',
            {
                method:'POST',
                body:formData
            }
        );

    const data =
        await response.json();

    const message =
        document.getElementById(
            'usernameMessage'
        );

    if(data.exists)
    {
        usernameAvailable = false;

        message.innerHTML =
            '❌ Username already exists';

        message.style.color =
            '#ff6b6b';
    }
    else
    {
        usernameAvailable = true;

        message.innerHTML =
            '✅ Username available';

        message.style.color =
            '#4CAF50';
    }
}

const emailInput =
    document.querySelector(
        'input[name="email"]'
    );

if(emailInput)
{
    emailInput.addEventListener(
        'blur',
        checkEmail
    );
}

async function checkEmail()
{
    const email =
        document
        .querySelector(
            'input[name="email"]'
        )
        .value
        .trim();

    const message =
        document.getElementById(
            'emailMessage'
        );

    if(email === '')
    {
        emailAvailable = false;

        message.innerHTML =
            '❌ Email is required';

        message.style.color =
            '#ff6b6b';

        return;
    }

    const formData =
        new FormData();

    formData.append(
        'type',
        'email'
    );

    formData.append(
        'value',
        email
    );

    const response =
        await fetch(
            'process/check_availability.php',
            {
                method: 'POST',
                body: formData
            }
        );

    const data =
        await response.json();

    if(data.exists)
    {
        emailAvailable = false;

        message.innerHTML =
            '❌ This email is already registered';

        message.style.color =
            '#ff4d4d';
    }
    else
    {
        emailAvailable = true;

        message.innerHTML =
            '✅ Email is available';

        message.style.color =
            '#4CAF50';
    }
}

document
.getElementById('registerForm')
.addEventListener(
'submit',
async function(e)
{
    e.preventDefault();

    const formData =
        new FormData(this);

    const response =
        await fetch(
            'process/signup_process.php',
            {
                method:'POST',
                body:formData
            }
        );

    const result =
        await response.text();

    if(result === 'success')
    {
        alert(
            '🎉 Account created successfully!'
        );

        window.location =
            'login.php';
    }
    else
    {
        alert(result);
    }
});

function checkPasswordStrength()
{
    const value =
        document.getElementById('password').value;

    const length =
        value.length >= 8;

    const upper =
        /[A-Z]/.test(value);

    const lower =
        /[a-z]/.test(value);

    const number =
        /\d/.test(value);

    const special =
        /[^A-Za-z0-9]/.test(value);

    updateRule('ruleLength', length);
    updateRule('ruleUpper', upper);
    updateRule('ruleLower', lower);
    updateRule('ruleNumber', number);
    updateRule('ruleSpecial', special);

    const score =
        [length, upper, lower, number, special]
        .filter(Boolean).length;

    const bar =
        document.getElementById('strengthBar');

    const text =
        document.getElementById('strengthText');

    if(score <= 2)
{
    text.innerHTML =
        '😭 Weak Password';

    bar.style.width =
        '33%';

    bar.style.background =
        '#ff4d4d';
}
else if(score <= 4)
{
    text.innerHTML =
        '😐 Average Password';

    bar.style.width =
        '66%';

    bar.style.background =
        '#ffaa00';
}
else
{
    text.innerHTML =
        '😎 Strong Password';

    bar.style.width =
        '100%';

    bar.style.background =
        '#4CAF50';
}
}

function updateRule(id, valid)
{
    const item =
        document.getElementById(id);

    const text =
        item.dataset.text;

    item.innerHTML =
        (valid ? '✓ ' : '✗ ') + text;

    item.className =
        valid ? 'valid' : 'invalid';
}

