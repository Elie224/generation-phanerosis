@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier les coordonnées de Don de Soutien</h1>
    <form action="{{ route('soutien.update', $info->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Banque</label>
            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $info->bank_name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Compte bancaire</label>
            <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account', $info->bank_account) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">IBAN</label>
            <input type="text" name="bank_iban" class="form-control" value="{{ old('bank_iban', $info->bank_iban) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Orange Money (numéro)</label>
            <input type="text" name="orange_money_number" class="form-control" value="{{ old('orange_money_number', $info->orange_money_number) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Orange Money (nom)</label>
            <input type="text" name="orange_money_name" class="form-control" value="{{ old('orange_money_name', $info->orange_money_name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">MTN Money (numéro)</label>
            <input type="text" name="mtn_money_number" class="form-control" value="{{ old('mtn_money_number', $info->mtn_money_number) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">MTN Money (nom)</label>
            <input type="text" name="mtn_money_name" class="form-control" value="{{ old('mtn_money_name', $info->mtn_money_name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse USDT</label>
            <input type="text" name="usdt_address" class="form-control" value="{{ old('usdt_address', $info->usdt_address) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse BTC</label>
            <input type="text" name="btc_address" class="form-control" value="{{ old('btc_address', $info->btc_address) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse ETH</label>
            <input type="text" name="eth_address" class="form-control" value="{{ old('eth_address', $info->eth_address) }}">
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('soutien.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection 
 
 