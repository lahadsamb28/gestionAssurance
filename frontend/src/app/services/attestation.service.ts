import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
// import { UserServiceService } from './user-service.service';

@Injectable({
  providedIn: 'root'
})
export class AttestationService {

  constructor(private httpClient: HttpClient) { }


  getPdf(id: number){
    return this.httpClient.get('http://127.0.0.1:8000/api/attestation/pdfOutput/'+id, {responseType: 'blob'});
  }
}
