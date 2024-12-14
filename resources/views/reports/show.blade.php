@extends('layouts.layout')

@section('content')
    <div class="card shadow mb-4 border-0 rounded-3 hover-card">
        <div class="card-body p-4">
            <h3 class="card-title text-primary mb-4 border-bottom pb-3 d-flex align-items-center">
                <i class="fas fa-file-alt me-3"></i>
                Deskripsi Kejadian
            </h3>
            <div class="row">
                @if ($report->image)
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="image-container">
                            <img src="{{ asset('storage/' . $report->image) }}" class="img-fluid rounded shadow hover-zoom"
                                alt="Report Image" data-bs-toggle="modal" data-bs-target="#imageModal">
                        </div>

                        <!-- Image Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0">
                                    <div class="modal-body p-0">
                                        <img src="{{ asset('storage/' . $report->image) }}" class="img-fluid"
                                            alt="Report Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-{{ $report->image ? '8' : '12' }}">
                    <div class="description-content p-3 rounded-3">
                        <div class="mb-3">
                            <strong class="text-primary">Tipe Kejadian:</strong>
                            <span class="ms-2">{{ $report->type }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-primary">Tanggal Kejadian:</strong>
                            <span class="ms-2">{{ $report->created_at->format('d F Y') }}</span>
                        </div>
                        <div>
                            <strong class="text-primary">Deskripsi:</strong>
                            <p class="mb-0">{{ $report->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 border-0 rounded-3 hover-card">
        <div class="card-body p-4">
            <h4 class="text-primary mb-3 pb-3 border-bottom d-flex align-items-center" style="cursor: pointer" onclick="toggleCommentList()">
                <i class="fas fa-comments me-3"></i>
                Komentar
            </h4>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn"
                    role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <p class="mb-0 text-dark">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    <h4 class="text-primary mb-3 d-flex align-items-center" style="cursor: pointer" onclick="toggleCommentForm()">
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
    </script>
@endsection