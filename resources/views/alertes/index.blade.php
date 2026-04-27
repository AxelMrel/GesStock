@extends('layouts.app')

@section('title', 'Alertes')
@section('page_title', 'Alertes')
@section('page_subtitle', 'Surveillance du stock')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Alertes de stock</h5>
        <small class="text-muted">
            {{ $nbNonLues }} alerte(s) non lue(s) sur {{ $alertes->total() }} au total
        </small>
    </div>

    @if(auth()->user()->role !== 'consultant' && $nbNonLues > 0)
        <form method="POST" action="{{ route('alertes.tout-lire') }}">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
            </button>
        </form>
    @endif
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#FEF3C7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-bell" style="color:#D97706"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $alertes->total() }}</div>
                    <div style="font-size:11px;color:#64748B">Total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#FEE2E2;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-bell" style="color:#DC2626"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $nbNonLues }}</div>
                    <div style="font-size:11px;color:#64748B">Non lues</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#D1FAE5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-check-circle" style="color:#16A34A"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $alertes->total() - $nbNonLues }}</div>
                    <div style="font-size:11px;color:#64748B">Lues</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-boxes" style="color:#2563EB"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">
                        {{ $alertes->getCollection()->pluck('article_id')->unique()->count() }}
                    </div>
                    <div style="font-size:11px;color:#64748B">Articles concernés</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABLEAU --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Message</th>
                        <th>Article</th>
                        <th>Stock actuel</th>
                        <th>Date</th>
                        @if(auth()->user()->role !== 'consultant')
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($alertes as $alerte)
                        <tr style="{{ !$alerte->lu ? 'background:#FFFBEB' : '' }}">

                            {{-- Statut --}}
                            <td>
                                @if(!$alerte->lu)
                                    <span class="badge" style="background:#FEF3C7;color:#92400E">
                                        <i class="fas fa-circle me-1" style="font-size:8px"></i>Non lue
                                    </span>
                                @else
                                    <span class="badge" style="background:#F1F5F9;color:#64748B">
                                        <i class="fas fa-check me-1"></i>Lue
                                    </span>
                                @endif
                            </td>

                            {{-- Message --}}
                            <td>
                                <div style="font-size:13px;{{ !$alerte->lu ? 'font-weight:500' : 'color:#64748B' }}">
                                    <i class="fas fa-exclamation-triangle me-1" style="color:#D97706"></i>
                                    {{ $alerte->message }}
                                </div>
                            </td>

                            {{-- Article --}}
                            <td>
                                @if($alerte->article)
                                    <span class="badge" style="background:#EFF6FF;color:#2563EB">
                                        {{ $alerte->article->nom }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:13px">Article supprimé</span>
                                @endif
                            </td>

                            {{-- Stock actuel --}}
                            <td>
                                @if($alerte->article)
                                    <div>
                                        <span class="fw-medium" style="color:{{ $alerte->article->estEnAlerte() ? '#DC2626' : '#16A34A' }}">
                                            {{ $alerte->article->quantite_stock }}
                                        </span>
                                        <span class="text-muted" style="font-size:11px">
                                            / min {{ $alerte->article->stock_minimum }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td>
                                <span class="text-muted" style="font-size:12px">
                                    {{ $alerte->created_at->format('d/m/Y H:i') }}
                                </span>
                                <br>
                                <small style="font-size:10px;color:#94A3B8">
                                    {{ $alerte->created_at->diffForHumans() }}
                                </small>
                            </td>

                            {{-- Actions --}}
                            @if(auth()->user()->role !== 'consultant')
                                <td>
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if(!$alerte->lu)
                                            <form method="POST" action="{{ route('alertes.lire', $alerte) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Marquer comme lue">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Supprimer"
                                                onclick="ouvrirModalSuppression({{ $alerte->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role !== 'consultant' ? 6 : 5 }}" class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted mb-0">Aucune alerte — tout va bien !</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($alertes->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $alertes->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- MODAL SUPPRIMER --}}
<div class="modal fade" id="modalSupprimer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <div style="width:56px;height:56px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto">
                        <i class="fas fa-trash" style="color:#EF4444;font-size:22px"></i>
                    </div>
                </div>
                <h6 class="fw-semibold mb-1">Supprimer l'alerte</h6>
                <p class="text-muted mb-0" style="font-size:13px">
                    Voulez-vous vraiment supprimer cette alerte ?
                </p>
            </div>
            <div class="modal-footer border-0 pt-2 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">
                    Annuler
                </button>
                <form method="POST" id="formSupprimer" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.ouvrirModalSuppression = function(id) {
            document.getElementById('formSupprimer').action = '{{ url("alertes") }}/' + id;
            new bootstrap.Modal(document.getElementById('modalSupprimer')).show();
        }
    });
</script>
@endpush