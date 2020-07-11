@extends('layouts.app') @section('header')
<style>
  .gallery-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-flow: dense;
  }
  @media (min-width: 768px) {
    .gallery-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }

  .gallery-item-wide {
    grid-column: auto/span 2;
  }

  .gallery-item-tall {
    grid-row: auto/span 2;
  }

  .grid-image {
    object-fit: cover;
    display: block;
    width: 100%;
    height: 100%;
    transition: 0.1s;
  }

  .grid-image:hover {
    filter: brightness(90%);
  }

  #gallery-modal {
    display: none;
    position: fixed;
    z-index: 20; 
    padding-top: 100px; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.9);
  }

  #modal-image {
    margin: auto;
    display: block;
    width: 100%;
  }

  #close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 30px;
    transition: 0.1s;
  }

  #close:hover,
  #close:focus {
    color: #bbb;
    cursor: pointer;
  }

  @media (min-width: 768px) {
    #modal-image {
      width: 80%;
    }
  }
</style>
@endsection @section('content')
<section class="flex justify-center mt-8 pt-8">
  <div>
    <div id="image-container" class="p-4 gallery-grid"></div>
    <div id="bottom"></div>
  </div>
</section>

<div id="gallery-modal" onclick="closeImage()">
  <span id="close" onclick="closeImage()">&times;</span>
  <img id="modal-image" />
</div>

@endsection @section('javascript')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var bottom = document.getElementById('bottom')
    var nextPage = '/api/images'

    var imageObserver = new IntersectionObserver(function(entries, observer) {
      currentBottomOffset = bottom.offsetTop < 100 ? 0 : bottom.offsetTop

      entries.forEach(function(entry) {
        if (entry.isIntersecting && nextPage !== null) {
          fetch(nextPage, {
            headers: {
              Accept: 'application/json',
            },
          })
            .then(response => response.json())
            .then(result => {
              result.images.data.forEach(image => {
                let div = document.createElement('div')
                div.classList.add('h-full')

                if (image.id % 4 === 0) {
                  div.classList.add('gallery-item-wide')
                } else if (image.id % 4 === 1) {
                  div.classList.add('gallery-item-tall')
                } else if (image.id % 4 === 2) {
                  div.classList.add('gallery-item-wide', 'gallery-item-tall')
                }

                let img = document.createElement('img')
                img.setAttribute('src', '/storage/' + image.filename)
                img.classList.add('grid-image')
                img.addEventListener("click", openModal)
                div.appendChild(img)

                document.getElementById('image-container').appendChild(div)
              })

              window.scrollTo(0, currentBottomOffset)

              nextPage = result.images.next_page_url
            })
        }
      })
    })

    imageObserver.observe(bottom)
  })

  let openModal = function(event) {
    document.getElementById('gallery-modal').style.display = 'block'
    document.getElementById('modal-image').src = event.target.src
  }

  let closeImage = function() {
    document.getElementById('gallery-modal').style.display = 'none'
  }
</script>

@endsection
