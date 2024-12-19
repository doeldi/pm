@extends('layouts.layout')

@section('content')
    <div class="card shadow mb-4 border-0 rounded-3 hover-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between  mb-3 pb-3 border-bottom align-items-center">
                <h3 class="card-title text-primary d-flex align-items-center">
                    <i class="fas fa-file-alt me-3"></i>
                    Deskripsi Kejadian
                </h3>
                <span class="badge bg-primary px-3 py-2 fs-6">{{ $report->type }}</span>
            </div>
            <div class="row">
                @if ($report->image)
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="image-container position-relative overflow-hidden rounded-3 shadow-sm">
                            <img src="{{ asset('storage/' . $report->image) }}"
                                class="img-fluid w-100 hover-zoom transition-all" alt="Report Image" data-bs-toggle="modal"
                                data-bs-target="#imageModal" style="cursor: pointer;">
                            <div
                                class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 transition-all">
                                <i class="fas fa-search-plus text-white fa-2x"></i>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-{{ $report->image ? '8' : '12' }}">
                    <div class="description-content p-4 rounded-3 bg-light shadow-sm border">
                        <div class="mb-3">
                            <strong class="text-primary d-inline-flex align-items-center">
                                <i class="far fa-calendar-alt me-2"></i>
                                Tanggal & lokasi Kejadian:
                            </strong>
                            <span class="ms-2">{{ $report->created_at->format('d F Y') }}, {{ $report->village }},
                                {{ $report->subdistrict }}, {{ $report->regency }}, {{ $report->province }}</span>
                        </div>
                        <div>
                            <strong class="text-primary d-inline-flex align-items-center">
                                <i class="fas fa-align-left me-2"></i>
                                Deskripsi:
                            </strong>
                            <p class="mt-2 mb-0 lh-base">{{ $report->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 border-0 rounded-3 hover-card">
        <div class="card-body p-4">
            <h4 class="text-primary mb-3 pb-3 border-bottom d-flex align-items-center" style="cursor: pointer"
                onclick="toggleCommentList()">
                <i class="fas fa-comments me-3"></i>
                Komentar
            </h4>
            <div id="commentList" style="display: none;">
                @if ($report->comments->isEmpty())
                    <div class="text-center py-5">
                        <i class="far fa-comment-dots fa-4x text-muted mb-3"></i>
                        <p class="text-muted fst-italic">Belum ada diskusi pada laporan ini</p>
                    </div>
                @else
                    <div class="comment-list border-bottom pb-3">
                        @foreach ($report->comments as $comment)
                            <div class="comment-item mb-4 animate__animated animate__fadeIn">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="bg-light rounded-4 p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0 text-primary">{{ $comment->user->email }}</h6>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted me-3">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </small>
                                                    @if (auth()->id() == $comment->user_id)  
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-muted p-0" type="button"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @if (
                                                                    $comment->created_at->diffInHours(now()) <= 1 &&
                                                                        $comment->id === $report->comments->where('user_id', auth()->id())->last()->id)
                                                                    <li>
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="editComment({{ $comment->id }}, '{{ $comment->comment }}')">
                                                                            <i class="fas fa-edit me-2"></i>Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                <li>
                                                                    <form
                                                                        action="{{ route('report.deleteComment', $comment->id) }}"
                                                                        method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger">
                                                                            <i class="fas fa-trash-alt me-2"></i>Hapus
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif                                                 </div>
                                            </div>
                                            <p class="mb-0 text-dark" id="comment-text-{{ $comment->id }}">
                                                {{ $comment->comment }}</p>
                                            <div id="edit-form-{{ $comment->id }}" style="display: none;" class="mt-3">
                                                <form action="{{ route('report.updateComment', $comment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-group">
                                                        <textarea class="form-control bg-white border rounded-3 shadow-sm mb-2" name="comment" rows="3" required
                                                            style="resize: none;">{{ $comment->comment }}</textarea>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm rounded-pill px-4">
                                                            <i class="fas fa-check me-2"></i>Simpan
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-secondary btn-sm rounded-pill px-4"
                                                            onclick="cancelEdit({{ $comment->id }})">
                                                            <i class="fas fa-times me-2"></i>Batal
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    <h4 class="text-primary mb-3 d-flex align-items-center" style="cursor: pointer"
                        onclick="toggleCommentForm()">
                        <i class="fas fa-plus-circle me-3"></i>
                        Tambah Komentar
                    </h4>
                    <form method="POST" action="{{ route('report.storeComment', $report->id) }}" class="comment-form"
                        id="commentForm" style="display: none;">
                        @csrf
                        <div class="mb-3">
                            <textarea name="comment" class="form-control border-0 bg-light shadow-sm rounded-3" rows="4"
                                placeholder="Tulis komentar Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill hover-button">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Komentar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCommentList() {
            const list = document.getElementById('commentList');
            list.style.display = list.style.display === 'none' ? 'block' : 'none';
        }

        function toggleCommentForm() {
            const form = document.getElementById('commentForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function editComment(commentId, commentText) {
            document.getElementById(`comment-text-${commentId}`).style.display = 'none';
            document.getElementById(`edit-form-${commentId}`).style.display = 'block';
        }

        function cancelEdit(commentId) {
            document.getElementById(`comment-text-${commentId}`).style.display = 'block';
            document.getElementById(`edit-form-${commentId}`).style.display = 'none';
        }
    </script>
    <style>
        .comment-form textarea {
            transition: all 0.3s ease;
        }

        .comment-form textarea:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border: none;
            background-color: #fff;
        }

        .info-card {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .modal-content {
            background-color: transparent;
        }

        .modal-body {
            padding: 0;
        }
    </style>
@endsection
