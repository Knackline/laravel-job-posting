<?php

namespace Knackline\LaravelJobPosting;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class JobPosting
{
    protected $title;
    protected $description;
    protected $educationRequirements;
    protected $employmentType;
    protected $experienceRequirements;
    protected $incentiveCompensation;
    protected $industry;
    protected $jobLocation;
    protected $datePosted;
    protected $jobBenefits;
    protected $occupationalCategory;
    protected $qualifications;
    protected $responsibilities;
    protected $salaryCurrency;
    protected $skills;
    protected $specialCommitments;
    protected $baseSalary;
    protected $workHours;

    // Additional fields from schema
    protected $applicantLocationRequirements;
    protected $applicationContact;
    protected $directApply;
    protected $eligibilityToWorkRequirement;
    protected $employerOverview;
    protected $jobImmediateStart;
    protected $jobLocationType;
    protected $jobStartDate;
    protected $physicalRequirement;
    protected $relevantOccupation;
    protected $securityClearanceRequirement;
    protected $sensoryRequirement;
    protected $totalJobOpenings;
    protected $validThrough;

    // Default values can be set via the configuration
    protected $defaultCountry;
    protected $defaultLanguage;

    public function __construct()
    {
        $this->defaultCountry = config('laravel-job-posting.default_country', 'US');
        $this->defaultLanguage = config('laravel-job-posting.default_language', 'en');
    }

    // Setters for the new fields
    public function setApplicantLocationRequirements(string $locationRequirements)
    {
        $this->applicantLocationRequirements = $locationRequirements;
        return $this;
    }

    public function setApplicationContact(array $contact)
    {
        $this->applicationContact = [
            '@type' => 'ContactPoint',
            'contactType' => $contact['contactType'],
            'telephone' => $contact['telephone'],
            'email' => $contact['email'],
        ];
        return $this;
    }

    public function setDirectApply(bool $directApply)
    {
        $this->directApply = $directApply;
        return $this;
    }

    public function setEligibilityToWorkRequirement(string $eligibility)
    {
        $this->eligibilityToWorkRequirement = $eligibility;
        return $this;
    }

    public function setEmployerOverview(string $overview)
    {
        $this->employerOverview = $overview;
        return $this;
    }

    public function setJobImmediateStart(bool $jobImmediateStart)
    {
        $this->jobImmediateStart = $jobImmediateStart;
        return $this;
    }

    public function setJobLocationType(string $locationType)
    {
        $this->jobLocationType = $locationType;
        return $this;
    }

    public function setJobStartDate(string $jobStartDate)
    {
        $this->jobStartDate = $jobStartDate;
        return $this;
    }

    public function setPhysicalRequirement(string $physicalRequirement)
    {
        $this->physicalRequirement = $physicalRequirement;
        return $this;
    }

    public function setRelevantOccupation(string $occupation)
    {
        $this->relevantOccupation = $occupation;
        return $this;
    }

    public function setSecurityClearanceRequirement(string $clearanceRequirement)
    {
        $this->securityClearanceRequirement = $clearanceRequirement;
        return $this;
    }

    public function setSensoryRequirement(string $sensoryRequirement)
    {
        $this->sensoryRequirement = $sensoryRequirement;
        return $this;
    }

    public function setTotalJobOpenings(int $totalJobOpenings)
    {
        $this->totalJobOpenings = $totalJobOpenings;
        return $this;
    }

    public function setValidThrough(string $validThrough)
    {
        $this->validThrough = $validThrough;
        return $this;
    }

    /**
     * Validate the mandatory fields before generating JSON-LD
     *
     * @throws InvalidArgumentException
     */
    public function validate()
    {
        $validator = Validator::make([
            'title' => $this->title,
            'description' => $this->description,
            'employmentType' => $this->employmentType,
            'jobLocation' => $this->jobLocation,
            'datePosted' => $this->datePosted,
            'validThrough' => $this->validThrough,
        ], [
            'title' => 'required|string',
            'description' => 'required|string',
            'employmentType' => 'required|string',
            'jobLocation' => 'required|array',
            'datePosted' => 'required|date_format:Y-m-d\TH:i:sP',
            'validThrough' => 'required|date_format:Y-m-d\TH:i:sP',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException("Validation failed: " . $validator->errors()->first());
        }
    }

    /**
     * Generate the JSON-LD structured data for the job posting.
     *
     * @return string
     */
    public function toJsonLd()
    {
        $this->validate();

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'baseSalary' => $this->baseSalary,
            'jobBenefits' => $this->jobBenefits,
            'datePosted' => Carbon::parse($this->datePosted)->toIso8601String(),
            'description' => $this->description,
            'educationRequirements' => $this->educationRequirements,
            'employmentType' => $this->employmentType,
            'experienceRequirements' => $this->experienceRequirements,
            'incentiveCompensation' => $this->incentiveCompensation,
            'industry' => $this->industry,
            'jobLocation' => $this->jobLocation,
            'directApply' => $this->directApply,
            'eligibilityToWorkRequirement' => $this->eligibilityToWorkRequirement,
            'employerOverview' => $this->employerOverview,
            'jobImmediateStart' => $this->jobImmediateStart,
            'jobLocationType' => $this->jobLocationType,
            'jobStartDate' => $this->jobStartDate,
            'physicalRequirement' => $this->physicalRequirement,
            'relevantOccupation' => $this->relevantOccupation,
            'securityClearanceRequirement' => $this->securityClearanceRequirement,
            'sensoryRequirement' => $this->sensoryRequirement,
            'title' => $this->title,
            'totalJobOpenings' => $this->totalJobOpenings,
            'validThrough' => Carbon::parse($this->validThrough)->toIso8601String(),
            'skills' => $this->skills,
            'specialCommitments' => $this->specialCommitments,
            'occupationalCategory' => $this->occupationalCategory,
            'qualifications' => $this->qualifications,
            'responsibilities' => $this->responsibilities,
            'salaryCurrency' => $this->salaryCurrency,
            'workHours' => $this->workHours,
        ];

        return json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
