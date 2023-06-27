import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserServiceService {
  private backend_url: string = 'http://127.0.0.1:8000/api/';
  private isLoggedIn = new BehaviorSubject<boolean>(false);
  private isAdmin = new BehaviorSubject<boolean>(false);

  constructor(private httpClient: HttpClient) { }

  getTokens(){
    const user: any = localStorage.getItem('user');
    const userObj = JSON.parse(user)

    const token = userObj.access_token;
    const httpOptions = {
      headers : new HttpHeaders({
      'Content-type': 'application/json',
      'Authorization': `Bearer ${token}`
    })}

    return httpOptions;
  }

  status(){
    const localData:any = localStorage.getItem('user');
    const user = JSON.parse(localData)
    if(!user){
      this.isLoggedIn.next(false);
      console.log("user not logged in !")
    }

    const userObj: any= localStorage.getItem('access_token');
    const access_token = JSON.parse(userObj);
    const token_expires_at = new Date(user.token_expires_at);
    const current_date = new Date();
    if(access_token){
      this.isLoggedIn.next(true);
      console.log(access_token)
    }else if(token_expires_at < current_date){
      this.isLoggedIn.next(false);
      console.log("session expired please signin again");
    }else{
      this.isLoggedIn.next(false);
      console.log("please signin again!")
    }

    return this.isLoggedIn.asObservable();
  }
  statusType(){
    const localData: any = localStorage.getItem('user_type');
    const user_type = JSON.parse(localData);

    if(user_type == "admin"){
      this.isAdmin.next(true)
    }else if(user_type == "simple"){
      console.log("you not authorized to access this page")
      this.isAdmin.next(false)
    }else{
      this.isAdmin.next(false)
    }
    return this.isAdmin.asObservable();
  }


  register(data: any): Observable<any>{
    return this.httpClient.post<any>(this.backend_url+'register', data, this.getTokens());
  }

  signin(data: any){
    return this.httpClient.post<any>(this.backend_url+'login', data);
  }
  show(){
    return this.httpClient.get(this.backend_url+'profil/show', this.getTokens());
  }
  update(data: any, id: number): Observable<any>{
    return this.httpClient.put(this.backend_url+'profil/'+id, data, this.getTokens());
  }
  edit(id: number): Observable<any>{
    return this.httpClient.get(this.backend_url+'profil/edit/'+id, this.getTokens());
  }
  logout(): Observable<any>{
    return this.httpClient.get<any>(this.backend_url+'logout', this.getTokens());
  }

}
