@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Bootstrap Test</h1>
            
            <!-- Basic Bootstrap Card Test -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            Test Card Header
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Card Title</h5>
                            <p class="card-text">This is a test card to verify Bootstrap is working properly.</p>
                            <button class="btn btn-primary">Test Button</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-check-circle"></i> Success Card
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Success State</h5>
                            <p class="card-text">This card tests Bootstrap icons and colors.</p>
                            <button class="btn btn-success">Success Button</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-warning">
                            <i class="bi bi-exclamation-triangle"></i> Shadow Card
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Shadow Effect</h5>
                            <p class="card-text">This card tests shadow effects.</p>
                            <button class="btn btn-warning">Warning Button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
