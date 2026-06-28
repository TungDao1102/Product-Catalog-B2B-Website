@props(['categories', 'activeCategorySlug' => null])

<div class="category-tree">
    <h5 class="mb-3">Danh mục</h5>
    <ul class="list-unstyled mb-0">
        @foreach($categories as $category)
            <li class="category-tree-item mb-1">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="category-tree-link text-decoration-none d-block py-1 {{ $activeCategorySlug === $category->slug ? 'active fw-bold' : '' }}">
                        <i class="bi bi-folder2 me-1"></i> {{ $category->name }}
                    </a>
                    @if($category->children->count() > 0)
                        <button class="btn btn-sm py-0 category-toggle border-0" type="button"
                                data-bs-toggle="collapse" data-bs-target="#cat-{{ $category->id }}"
                                aria-expanded="false">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    @endif
                </div>
                @if($category->children->count() > 0)
                    <ul class="collapse list-unstyled ms-3" id="cat-{{ $category->id }}">
                        @foreach($category->children as $child)
                            <li class="category-tree-item mb-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('categories.show', $child->slug) }}"
                                       class="category-tree-link text-decoration-none d-block py-1 {{ $activeCategorySlug === $child->slug ? 'active fw-bold' : '' }}">
                                        <i class="bi bi-folder me-1"></i> {{ $child->name }}
                                    </a>
                                    @if($child->children->count() > 0)
                                        <button class="btn btn-sm py-0 category-toggle border-0" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#cat-{{ $child->id }}"
                                                aria-expanded="false">
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                    @endif
                                </div>
                                @if($child->children->count() > 0)
                                    <ul class="collapse list-unstyled ms-3" id="cat-{{ $child->id }}">
                                        @foreach($child->children as $grandchild)
                                            <li>
                                                <a href="{{ route('categories.show', $grandchild->slug) }}"
                                                   class="category-tree-link text-decoration-none d-block py-1 {{ $activeCategorySlug === $grandchild->slug ? 'active fw-bold' : '' }}">
                                                    <i class="bi bi-dot me-1"></i> {{ $grandchild->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
