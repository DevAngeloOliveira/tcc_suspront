@extends('layouts.app')

@section('title', 'Documentação da API')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Documentação da API SusPront</h4>
            </div>
            <div class="card-body markdown-body">
                <div id="markdown-content">
                    {{ $markdown }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5.2.0/github-markdown.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const markdownContent = document.getElementById('markdown-content');
            if (markdownContent) {
                markdownContent.innerHTML = marked.parse(markdownContent.textContent);
            }
        });
    </script>
    <style>
        .markdown-body {
            box-sizing: border-box;
            min-width: 200px;
            max-width: 980px;
            margin: 0 auto;
            padding: 45px;
        }

        @media (max-width: 767px) {
            .markdown-body {
                padding: 15px;
            }
        }
    </style>
@endsection
