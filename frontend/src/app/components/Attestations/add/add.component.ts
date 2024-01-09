import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Subscription } from 'rxjs';

/**
 * To Do : radio button
 */

@Component({
  selector: 'app-add',
  templateUrl: './add.component.html',
  styleUrls: ['./add.component.scss'],
})
export class AddComponent implements OnInit{
  TypeCertificat: any = [
    {
      type: 'standard',
      duree: '1 mois',
      logo: '../../../../assets/standard1.png'
    },
    {
      type: 'premium',
      duree: '3 mois',
      logo: '../../../../assets/standard.png'
    },
    {
      type: 'vip',
      duree: '12 mois',
      logo: '../../../../assets/vip-24.png'
    },
  ];


  Sexe: any = ['HOMME', 'FEMME'];
  // typeDeVehicule:any = ['']
  step: number = 0;
  current_fs = document.getElementById("field"+this.step);

  attestationForm!: FormGroup;

  selectedTypeC!: Subscription;
  typeOfCertificat = '';

  constructor(private fb: FormBuilder){}

  ngOnInit(): void {
    this.attestationForm = this.fb.group({
      typeC: new FormControl(null, [Validators.required]),
      nom: new FormControl(null, [Validators.required]),
      prenom: new FormControl(null, [Validators.required]),
      sexe: new FormControl('', [Validators.required]),
      dateDeNaissance: new FormControl(null, [Validators.required]),
      adresse: new FormControl(null, [Validators.required]),
      telephone: new FormControl(null, [Validators.required, Validators.pattern("(221)(77|78|76|75)[0-9]{7}")]),
      email: new FormControl(null, [Validators.required, Validators.email]),
      profession: new FormControl(null, [Validators.required]),
      typeDeVehicule: new FormControl(null, [Validators.required]),
      immatriculation: new FormControl(null, [Validators.required]),
      categorie: new FormControl(null, [Validators.required]),
      marque: new FormControl(null, [Validators.required]),
      model: new FormControl(null, [Validators.required]),
      annee: new FormControl(null, [Validators.required]),
      transmission: new FormControl(null, [Validators.required]),
      energie: new FormControl(null, [Validators.required]),
    })

    this.selectedTypeC = this.attestationForm.controls['typeC'].valueChanges.subscribe((res: any) => {
      this.typeOfCertificat = res;
      console.log(res)
    })

  }

  ngOnDestroy(){
    this.selectedTypeC.unsubscribe();
  }

  get Att(){
    return this.attestationForm.controls;
  }

  nextStep(){
    this.current_fs?.animate({opacity: 0.5}, {duration: 600})
    this.step = this.step + 1;
  }

  previousStep(){
    this.step = this.step - 1;
    this.current_fs?.animate({opacity: 0.5}, {duration: 600})
  }

}
