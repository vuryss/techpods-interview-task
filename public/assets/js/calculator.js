const expressionInput = document.getElementById('calculator-expression');
const calculateButton = document.getElementById('calculate-result');
const errorOutput = document.getElementById('error-output');

const evaluateExpression = () => {
    const formData = new FormData();
    formData.append('expression', expressionInput.value);

    fetch(
        '/calculator/evaluate',
        {
            method: 'POST',
            body: formData
        }
    )
        .then(response => response.json())
        .then(response => {
            if (response.hasOwnProperty('error')) {
                errorOutput.textContent = response.error;
                return;
            }

            if (response.hasOwnProperty('result')) {
                errorOutput.textContent = '';
                expressionInput.value = response.result;
                return;
            }

            errorOutput.textContent = 'Server error. Cannot parse response.';
        })
        .catch(error => {
            errorOutput.textContent = 'Server error. Cannot parse response.';
        });

    expressionInput.focus();
}

document.querySelectorAll('.buttons-wrapper > button:not(#calculate-result)').forEach(button => {
    button.addEventListener('click', () => {
        expressionInput.value += button.textContent;
        expressionInput.focus();
    })
})

calculateButton.addEventListener('click', evaluateExpression);
expressionInput.addEventListener('keydown', e => {
    if (e.code === 'Enter' || e.code === 'NumpadEnter') {
        evaluateExpression();
    }
})

document.addEventListener('DOMContentLoaded', () => {
    expressionInput.focus();
})
