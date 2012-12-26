<?php Section::inject('page_title', 'Application Received') ?>
<div class="container">
  <p class="main-description">You should receive an email confirmation soon!</p>
</div>
<div class="readable-width">
  <p>
    <strong>WHITE HOUSE PRESIDENTIAL INNOVATION FELLOWS EEO SURVEY – APPLICANT VOLUNTARY SELF-IDENTIFICATION FORM</strong>
  </p>
  <p>
    Providing this information is voluntary and providing or refusing to provide it will not subject you to any adverse treatment.
    The information you provide will only be used for equal employment and diversity recordkeeping and reporting required by law.
    The information you provide is also confidential.
  </p>
  <p>
    To comply with federal equal employment opportunity recordkeeping and reporting requirements, the White House
    offers applicants the opportunity to complete this self-identification form to obtain certain demographic information.
    If you choose not to self-identify, please check the box at the bottom of this page, or you may elect not to complete the form.
    The information will be used only in accordance with the provisions of applicable laws, regulations and executive orders,
    including those that require information to be summarized and reported to the government.
  </p>
</div>
<hr />
<form action="<?php echo e(route('vendor_applied', $vendor->demographic_survey_key)); ?>" method="POST">
  <fieldset>
    <h5>Gender</h5>
    <select name="gender">
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="Other">Other</option>
    </select>
    <hr />
    <h5>Race and Ethnicity</h5>
    <strong>Are you A:</strong>
    <label class="checkbox">
      <input type="checkbox" name="race[]" value="hispanic_latino" data-checkbox-group="a" />
      Hispanic or Latino – A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race.
    </label>
    <strong>OR B: (select up to two)</strong>
    <label class="checkbox">
      <input type="checkbox" name="race[]" value="white" data-checkbox-group="race" data-checkbox-max="2" />
      White (Not Hispanic or Latino) – A person having origins in any of the original peoples of Europe, the Middle East, or North Africa.
    </label>
    <label class="checkbox">
      <input type="checkbox" name="race[]" value="black" data-checkbox-group="race" data-checkbox-max="2" />
      Black or African American (Not Hispanic or Latino) – A person having origins in any of the black racial groups of Africa.
    </label>
    <label class="checkbox">
      <input type="checkbox" name="race[]" value="pacific_islander" data-checkbox-group="race" data-checkbox-max="2" />
      Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) – A person having origins in any of the peoples of Hawaii, Guam, Samoa, or other Pacific Islands.
    </label>
    <label class="checkbox">
      <input type="checkbox" name="race[]" value="asian" data-checkbox-group="race" data-checkbox-max="2" />
      Asian (Not Hispanic or Latino) – A person having origins in any of the original peoples of the Far East, Southeast Asia, or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand, and Vietnam.
    </label>
    <label class="checkbox">
      <input type="checkbox" name="race[]" value="american_indian" data-checkbox-group="race" data-checkbox-max="2" />
      American Indian or Alaska Native (Not Hispanic or Latino) – A person having origins in any of the original peoples of North and South America (including Central America), and who maintain tribal affiliation or community attachment.
    </label>
  </fieldset>
  <div class="form-actions">
    <button class="btn btn-primary">Submit</button>
  </div>
</form>