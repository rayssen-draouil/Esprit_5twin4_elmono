@if ($paginator->hasPages())
    <style>
        .aquasecure-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--line, #dce7e5);
            font-family: inherit;
        }
        .pagination-pages {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .pagination-pages .page-item {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .pagination-pages .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink, #172c32);
            background: #ffffff;
            border: 1px solid var(--line, #dce7e5);
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            user-select: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            box-sizing: border-box;
            gap: 6px;
        }
        .pagination-pages a.page-link:hover {
            background: var(--soft, #e5f5f1);
            border-color: var(--aqua, #16b7a3);
            color: var(--deep, #073f4a);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(22, 183, 163, 0.15);
        }
        .pagination-pages .page-item.disabled .page-link {
            background: #f7faf9;
            border-color: #e5eeee;
            color: #a8bcba;
            cursor: not-allowed;
            opacity: 0.65;
            box-shadow: none;
        }
        .pagination-svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            display: inline-block !important;
            vertical-align: middle !important;
            flex-shrink: 0;
        }
    </style>
    <nav class="aquasecure-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="pagination-pages pagination-simple" role="list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">
                        <svg class="pagination-svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Précédent</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <svg class="pagination-svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Précédent</span>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span>Suivant</span>
                        <svg class="pagination-svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">
                        <span>Suivant</span>
                        <svg class="pagination-svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
