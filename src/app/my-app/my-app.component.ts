import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule,FormGroup,FormControl } from '@angular/forms';
import { CommonModule } from '@angular/common';

//----------http api

import { HttpClient, HttpEventType, HttpHeaders } from '@angular/common/http';
import { of  } from 'rxjs';
import { catchError, map  } from 'rxjs/operators';


@Component({
  selector: 'app-my-app',
  standalone: true,
  imports: [CommonModule,FormsModule,ReactiveFormsModule],
  templateUrl: './my-app.component.html',
  styleUrl: './my-app.component.css'
})
export class MyAppComponent {
  
  productProfileForm = new FormGroup({
    pCode: new FormControl(''),
    pName: new FormControl(''),
    pLine: new FormControl(''),
    pScale: new FormControl(''),
    pVendor: new FormControl(''),
    pDescription: new FormControl(''),
    quanity: new FormControl(''),
    buyprice: new FormControl(''),
    msrp: new FormControl('')
  });

  onSubmit() {
   // console.warn(this.productProfile.value);
    this.http.post<number>(
        'http://localhost/bo/products_insert.php', 
        this.productProfileForm.value,
    ).subscribe({
        next:(resp:number)=>{
            console.log(resp);
        },
        error:(err)=> alert('errrrrrrrr')
    });
  }

  onSubmit2():void{
    const hd = new HttpHeaders()
    .set('content-type', 'application/x-www-form-urlencoded');
    this.http.post<number>('http://localhost/bo/products_insert.php',
       this.productProfileForm.value, 
      {
      reportProgress: true,
      observe: 'events',
      headers:hd
      },
  ).subscribe(event => {
      switch (event.type) {
        case HttpEventType.UploadProgress:
          console.log('Uploaded ' + event.loaded + ' out of ' + event.total + ' bytes');
          break;
        case HttpEventType.Response:
          console.log('Finished uploading!' + event.body);
          break;
      }
    });
    
  }

  products$ !: ProductProfile[] | null;

  searchkey={'key':'a'};
  
  getProducts() {
    this.http
         .get<ProductProfile[]>(
            'http://localhost/bo/fetch-product.php',
            {params: this.searchkey}
         ).pipe(
             catchError( () => of(null) ),
             map( (data) => {
                 if(data == null){ return [];}
                 else {return data;}
             })
        ).subscribe((data)=>{
             this.products$ = data;
           //  console.log(data);
             console.log(this.products$);
         }
        );
   }
   getProducts2() {
    this.http
         .post <ProductProfile[]>(
            'http://localhost/bo/fetch-product.php',
             this.searchkey,
             {
                 headers:{"Content-Type" : "application/json; charset=UTF-8"}
              },
        ).subscribe( 
            (resp:any) => {  this.products$ = resp ; },
        );
   }
   constructor(private http: HttpClient) {
    // This service can now make HTTP requests via `this.http`.
  }
}

export interface ProductProfile{
  productCode : string;
  productName : string;
  productLine : string;
  productScale : string;
  productVendor : string;
  productDescription : string;
  quantityInStock : number;
  buyPrice : number;
  MSRP : number;
}