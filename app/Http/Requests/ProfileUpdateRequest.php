<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            
            // Informations personnelles
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female'],
            
            // Informations spirituelles
            'conversion_date' => ['nullable', 'date', 'before_or_equal:today'],
            'baptism_date' => ['nullable', 'date', 'before_or_equal:today'],
            'ministry_role' => ['nullable', 'string', 'max:100'],
            'spiritual_gifts' => ['nullable', 'string', 'max:500'],
            'testimony' => ['nullable', 'string', 'max:2000'],
            
            // Informations d'église
            'member_since' => ['nullable', 'date', 'before_or_equal:today'],
            'age_group' => ['nullable', 'in:jeunesse,adultes,seniors'],
            'ministries' => ['nullable', 'array'],
            'ministries.*' => ['string', 'max:100'],
            'small_group' => ['nullable', 'string', 'max:100'],
            
            // Réseaux sociaux
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            
            // Préférences
            'privacy_level' => ['nullable', 'in:public,members,friends,private'],
            'language' => ['nullable', 'in:fr,en'],
            'theme' => ['nullable', 'in:light,dark,auto'],
            'notification_preferences' => ['nullable', 'array'],
            'notification_preferences.email' => ['boolean'],
            'notification_preferences.sms' => ['boolean'],
            'notification_preferences.push' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'avatar.max' => 'L\'avatar ne doit pas dépasser 2MB.',
            'banner.max' => 'La bannière ne doit pas dépasser 5MB.',
            'birth_date.before' => 'La date de naissance doit être dans le passé.',
            'conversion_date.before_or_equal' => 'La date de conversion ne peut pas être dans le futur.',
            'baptism_date.before_or_equal' => 'La date de baptême ne peut pas être dans le futur.',
            'member_since.before_or_equal' => 'La date d\'adhésion ne peut pas être dans le futur.',
        ];
    }
}
