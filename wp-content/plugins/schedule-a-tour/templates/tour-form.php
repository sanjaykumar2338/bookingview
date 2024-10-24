<?php if ( isset($_GET['tour_success']) && $_GET['tour_success'] == '1' ): ?>
    <div class="alert alert-success">
        Your tour has been successfully scheduled! We will contact you shortly.
    </div>
<?php endif; ?>
<br>
<button id="showFormButton" class="rh-btn rh-btn-primary btn btn-primary schedule_a_tour">Schedule a Tour</button>

<div id="tourForm" style="display: none;">
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="tour_form">
        <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>"> <!-- Pass the post ID -->

        <!-- Step 1: Date and Time -->
        <div id="step-1">
            <h4 class="rh_property__heading" style="font-size: 28px;">Schedule a Tour</h4>

            <div class="form-group">
                <label for="tourDate">Choose Date:</label>
                <input type="date" id="tourDate" name="tour_date" class="form-control" required onfocus="'showPicker' in this && this.showPicker()">
            </div>

            <div class="form-group">
                <label for="tourTime">Choose Time:</label>
                <input type="time" id="tourTime" name="tour_time" class="form-control" required onfocus="'showPicker' in this && this.showPicker()">
            </div>

            <button id="nextStep" type="button" class="rh-btn rh-btn-primary">Next</button>
        </div>

        <!-- Step 2: Personal Details (Initially hidden) -->
        <div id="step-2" style="display: none;">
            <div class="form-group">
                <label for="yourName">Your Name:</label>
                <input type="text" id="yourName" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="yourEmail">Your Email:</label>
                <input type="email" id="yourEmail" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="yourPhone">Your Phone Number:</label>
                <input type="tel" id="yourPhone" name="phone" class="form-control" required>
            </div>

            <input type="hidden" name="action" value="tour_form"> <!-- Hidden action field -->
            <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>"> <!-- To save the post ID -->

            <button type="submit" name="tour_submit" class="rh-btn rh-btn-primary">Schedule</button>
            <button id="backStep" type="button" class="rh-btn rh-btn-primary">Back</button>
        </div>
    </form>
</div>

<!-- Custom Styles -->
<style>
    #tourForm {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f5f5f5;
    }

    #tourForm label {
        display: block;
        margin-bottom: 5px;
        text-align: left;
    }

    #tourForm input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    #tourForm button {
        padding: 10px 20px;
        background-color: #1CB2FF;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    #tourForm button:hover {
        background-color: #1CB2FF;
    }

    .schedule_a_tour {
        margin-bottom: 13px;
    }

    #showFormButton{
        display: block;
    }

    .alert {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        margin-top: 20px;
        border-radius: 3px;
    }

    .alert-success {
        background-color: #4CAF50;
    }

</style>

<!-- JavaScript -->
<script>
    const showFormButton = document.getElementById('showFormButton');
    const tourForm = document.getElementById('tourForm');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const nextStepButton = document.getElementById('nextStep');
    const backStepButton = document.getElementById('backStep');

    // Show form when "Schedule a Tour" button is clicked
    showFormButton.addEventListener('click', () => {
        tourForm.style.display = 'block';
        showFormButton.style.display = 'none';
    });

    // Move to step 2 when "Next" is clicked, and validate date and time
    nextStepButton.addEventListener('click', () => {
        const date = document.getElementById('tourDate').value;
        const time = document.getElementById('tourTime').value;
        if (date && time) {
            step1.style.display = 'none';
            step2.style.display = 'block';
        } else {
            alert('Please select a date and time.');
        }
    });

    // Go back to step 1 when "Back" is clicked
    backStepButton.addEventListener('click', () => {
        step1.style.display = 'block';
        step2.style.display = 'none';
    });

    setTimeout(() => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('tour_success')) {
            // Scroll to the form section with the id 'tourForm'
            const formSection = document.querySelector('.rh_property__sidebar');
            if (formSection) {
                formSection.scrollIntoView({ behavior: 'smooth' });
            }
        } 
    }, 1000);

    setTimeout(function() {
        var successMessage = document.querySelector('.alert-success');
        if (successMessage) {
            successMessage.style.transition = "opacity 0.5s ease-out";
            successMessage.style.opacity = "0";
            setTimeout(function() {
                successMessage.remove();
            }, 500); // Allow for transition to complete
        }
    }, 6000);
</script>
