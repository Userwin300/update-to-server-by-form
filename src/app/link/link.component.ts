import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule, FormGroup, FormControl, FormBuilder } from '@angular/forms';
import { CommonModule } from '@angular/common';

//----------http api

import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { of } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Component({
  selector: 'app-link',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './link.component.html',
  styleUrls: ['./link.component.css'] // Fixed 'styleUrl' to 'styleUrls'
})
export class LinkComponent {

  ProductForm = new FormGroup({
    productCode: new FormControl(''),
    productName: new FormControl(''),
    productLine: new FormControl(''),
    productScale: new FormControl(''),
    productVendor: new FormControl(''),
    productDescription: new FormControl(''),
    quantityInStock: new FormControl(''),
    buyPrice: new FormControl(''),
    MSRP: new FormControl('')
  });

  products$!: ProductProfile[] | null;

  find = '';
  searchkey = { 'key': this.find };

  conlog(){
    console.log('Find Key search : '+this.find);
    this.getProducts(this.find);
  }

  constructor(private http: HttpClient) {
    // This service can now make HTTP requests via `this.http`.
  }

  getProducts(key:string) {
    this.http
      .get<ProductProfile[]>(
        'http://localhost/bo/get_Pro.php',
        { params: { 'key': key } }
      ).pipe(
        catchError(() => of([])),
        map((data) => {
          return data || [];
        })
      ).subscribe((data) => {
        this.products$ = data;
        console.log(this.products$);
      });
  }

  getProducts2() {
    this.http
      .post<ProductProfile[]>(
        'http://localhost/bo/get_Pro.php',
        this.searchkey,
        {
          headers: { "Content-Type": "application/json; charset=UTF-8" }
        },
      ).subscribe(
        (resp: any) => { this.products$ = resp; },
      );
  }

  onUpdate(): void {
    const url = 'http://localhost/bo/update_product.php';
    const formData = this.ProductForm.value;

    // Log form data to verify it contains all required fields
    console.log('Form Data:', formData);

    this.http.post<any>(url, formData, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json' // Ensure JSON content type
      })
    })
      .subscribe({
        next: response => {
          console.log('Server Response:', response);
          if (response.success) {
            console.log('Update successful!');
          } else {
            console.error('Update failed:', response.error);
          }
        },
        error: (error: HttpErrorResponse) => {
          console.error('There was an error:', error.message);
          console.error('Full error response:', error);
        }
      });
  }

}

export interface ProductProfile {
  productCode: string;
  productName: string;
  productLine: string;
  productScale: string;
  productVendor: string;
  productDescription: string;
  quantityInStock: number;
  buyPrice: number;
  MSRP: number;
}
