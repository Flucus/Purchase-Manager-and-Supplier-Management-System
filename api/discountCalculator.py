from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)


@app.route('/api/discountCalculator', methods=['GET'])
def calculate_discount():
    total_order_amount = request.args.get('TotalOrderAmount')

    if total_order_amount is None:
        return jsonify({'error': 'TotalOrderAmount query parameter is missing.'}), 400

    try:
        total_order_amount = float(total_order_amount)
    except ValueError:
        return jsonify({'error': 'Invalid value for TotalOrderAmount.'}), 400

    discount_rate = 0.0
    if total_order_amount >= 10000:
        discount_rate = 0.13
    elif total_order_amount >= 5000:
        discount_rate = 0.06
    elif total_order_amount >= 3000:
        discount_rate = 0.03

    new_order_amount = total_order_amount * (1 - discount_rate)

    response = {
        'DiscountRate': discount_rate,
        'NewOrderAmount': new_order_amount
    }

    return jsonify(response)


if __name__ == '__main__':
    app.run(host='127.0.0.1', port=8011)
