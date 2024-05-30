<?php
/*
Plugin Name: Sleep Apnea Questionnaire
Description: A simple plugin to calculate sleep apnea risk.
Version: 1.0
Name: Austin O'Neil - ProspectaMarketing
*/

function saq_display_form() {
    ob_start();
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        .question {
            margin-bottom: 15px;
        }
        .result, .consultation-message, .contact-button {
            display: none;
            margin-top: 20px;
            font-weight: bold;
        }
        .contact-button {
            margin-top: 10px;
        }
    </style>
    <h2>Sleep Apnea Questionnaire Form</h2>
    <form id="saq-form" method="post">
        <div class="question">
            <label>1. Do you snore loudly (loud enough to be heard through closed doors or your bed-partner expresses concerns about your snoring)?</label>
            <label><input type="radio" name="q1" value="yes">Yes</label>
            <label><input type="radio" name="q1" value="no">No</label>
        </div>
        <div class="question">
            <label>2. Do you often feel Tired, Fatigued, or Sleepy during the daytime (such as falling asleep during driving or talking to someone)?</label>
            <label><input type="radio" name="q2" value="yes"> Yes</label>
            <label><input type="radio" name="q2" value="no" checked> No</label>
        </div>
        <div class="question">
            <label>3. Has anyone Observed you Stop Breathing or Choking/Gasping during your sleep?</label>
            <label><input type="radio" name="q3" value="yes"> Yes</label>
            <label><input type="radio" name="q3" value="no" checked> No</label>
        </div>
        <div class="question">
            <label>4. Do you have or are you being treated for high blood pressure?</label>
            <label><input type="radio" name="q4" value="yes"> Yes</label>
            <label><input type="radio" name="q4" value="no" checked> No</label>
        </div>
        <div class="question">
            <label>5. Are you older than 50?</label>
            <label><input type="radio" name="q5" value="yes"> Yes</label>
            <label><input type="radio" name="q5" value="no" checked> No</label>
        </div>
        <div class="question">
            <label>6. Do you have a large neck size? (is your shirt collar 16 inches / 40 cm or larger?)</label>
            <label><input type="radio" name="q6" value="yes"> Yes</label>
            <label><input type="radio" name="q6" value="no" checked> No</label>
        </div>
        <div class="question">
            <label>7. Are you biologically male?</label>
            <label><input type="radio" name="q7" value="yes"> Yes</label>
            <label><input type="radio" name="q7" value="no" checked> No</label>
        </div>
        <div class="question">
            <label>8. Enter your height and weight:</label>
            <input type="number" name="feet" placeholder="Feet" required> ft
            <input type="number" name="inches" placeholder="Inches" required> in
            <input type="number" name="weight" placeholder="Weight in Pounds" required> lbs
        </div>
        <button type="submit">Calculate Risk</button>
    </form>
        <div class="result" id="saq-result"></div>
        <div class="consultation-message" id="consultation-message">
            We advise contacting our specialists to set up a sleep apnea consultation.
        </div>
        <button class="contact-button" id="contact-button" onclick="window.location.href='/contact-us'">Contact Us</button>
    <script>
        document.getElementById('saq-form').addEventListener('submit', function(event) {
            event.preventDefault();

            let yesCount = 0;
            let firstFourYesCount = 0;
            let questions = ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7'];

            questions.forEach(function(question) {
                let value = document.querySelector('input[name="' + question + '"]:checked').value;
                if (value === 'yes') {
                    yesCount++;
                    if (['q1', 'q2', 'q3', 'q4'].includes(question)) {
                        firstFourYesCount++;
                    }
                }
            });

            let male = document.querySelector('input[name="q7"]:checked').value === 'yes';
            let largeNeck = document.querySelector('input[name="q6"]:checked').value === 'yes';

            let feet = parseFloat(document.querySelector('input[name="feet"]').value);
            let inches = parseFloat(document.querySelector('input[name="inches"]').value);
            let weight = parseFloat(document.querySelector('input[name="weight"]').value);

            let heightInInches = feet * 12 + inches;
            let heightInMeters = heightInInches * 0.0254;
            let bmi = weight * 0.453592 / (heightInMeters * heightInMeters);

            let risk = "Low Risk";

            if (yesCount >= 5 || (firstFourYesCount >= 2 && male) || (firstFourYesCount >= 2 && bmi > 35) || (firstFourYesCount >= 2 && largeNeck)) {
                risk = "High Risk";
            } else if (yesCount >= 3) {
                risk = "Medium Risk";
            }

            document.getElementById('saq-result').innerText = "Your risk level is: " + risk;

            if (risk === "Medium Risk" || risk === "High Risk") {
                document.getElementById('consultation-message').style.display = 'block';
                document.getElementById('contact-button').style.display = 'block';
            }

            document.getElementById('saq-form').style.display = 'none';
            document.getElementById('saq-result').style.display = 'block';
        });
    </script>
    <?php
    return ob_get_clean();
}

function saq_shortcode() {
    add_shortcode('sleep_apnea_questionnaire', 'saq_display_form');
}

add_action('init', 'saq_shortcode');