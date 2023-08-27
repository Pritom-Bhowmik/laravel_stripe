<!DOCTYPE html>
<html lang="en">

<head>
    <title>Stripe Payment In Laravel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="card my-5">
                <div class="card-body">
                    <h1 class="text-uppercase text-center">Make A Payment</h1>
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <form action="{{ route('stripe.store') }}" method="POST" id="card-form">
                        @csrf
                        <div class="mb-3">
                            <label for="card-name"
                                class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Your
                                name</label>
                            <input type="text" name="name" id="card-name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email"
                                class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="card"
                                class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Card
                                details</label>

                            <div class="bg-gray-100 p-6 rounded-xl">
                                <div id="card"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="text-white btn btn-success">Pay 👉</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe('{{ env('STRIPE_KEY') }}')
    const elements = stripe.elements()
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px'
            }
        }
    })
    const cardForm = document.getElementById('card-form')
    const cardName = document.getElementById('card-name')
    cardElement.mount('#card')
    cardForm.addEventListener('submit', async (e) => {
        e.preventDefault()
        const {
            paymentMethod,
            error
        } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: cardName.value
            }
        })
        if (error) {
            console.log(error)
        } else {
            let input = document.createElement('input')
            input.setAttribute('type', 'hidden')
            input.setAttribute('name', 'payment_method')
            input.setAttribute('value', paymentMethod.id)
            cardForm.appendChild(input)
            cardForm.submit()
        }
    })
</script>

</body>

</html>
