<template>
	<section class="section-search-page">
		<div class="inner" v-bind:class="{ active: isActive}">
			<div class="wrapper">
				<div class="type-select">
					<select v-on:change="selection" name="type-select">
						<option value="" disabled selected>Type of Student</option>
						<option value="high schooler">High schooler</option>
						<option value="freshman">Freshman</option>
						<option value="sophomore">Sophomore</option>
						<option value="junior">Junior</option>
						<option value="senior">Senior</option>
					</select>
				</div>
				<div class="search">
					<input v-model="input" v-on:keyup.enter="create_post" type="search" v-bind:placeholder="input_placeholder">
				</div>
				<div class="button">
					<button @click="create_post" tabindex="0">Search</button>
				</div>
				<div class="filter-by" @click="filter_open()" v-on:keyup.enter="filter_open()">
					<span class="filter-icon"></span>
					<div class="filter-all">
						<h1 class="filter" tabindex="0">Filter By</h1>
					</div>
				</div>
			</div>

			<ul class="filter-category" >
				<li v-for="item in checked" @click="deleteProduct(item)" :key="item">
					{{ item }}
				</li>
			</ul>
			<div class="sub-filter tabs" id="filter_list"  >
				<div class="filter-tab tabs-nav">
					<button @click="ActiveCheckbox1(7)"   ref="focusMe" v-on:keyup.enter="open_tab()">Ethnicity</button>
					<button @click="ActiveCheckbox1(1)" >Gender</button>
					<button @click="ActiveCheckbox1(2)" >Disability</button>
					<button @click="ActiveCheckbox1(3)" >State of Residence</button>
					<button @click="ActiveCheckbox1(8)" >Area of study</button>
					<button @click="ActiveCheckbox1(9)" >Type of Study</button>
					<button @click="ActiveCheckbox1(4)" >Amount (minimum award amount)</button>
					<button @click="ActiveCheckbox1(5)" >Application Deadline</button>
					<button @click="ActiveCheckbox1(6)" >Financial Need</button>
				</div>

				<div class="filter-tab tab " v-bind:class="{ hidden: Active7 }">
					<a href="#">Ethnicity</a>
					<div class="checkbox" ref="seven">
						<input type="checkbox" id="African American" value="African American"
							   v-model="checked" @click="filter_checked" ref="sev">
						<label for="African American">African American</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="American Indian" value="American Indian"
							   v-model="checked" @click="filter_checked">
						<label for="American Indian">American Indian</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="Hispanic" value="Hispanic"
							   v-model="checked" @click="filter_checked">
						<label for="Hispanic">Hispanic</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="Asian American" value="Asian American"
							   v-model="checked" @click="filter_checked">
						<label for="Asian American">Asian American</label>
					</div>
				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active1 }">
					<a href="#">Gender</a>
					<div class="checkbox">
						<input type="checkbox" id="male" value="male"
							   v-model="checked" @click="filter_checked">
						<label for="male">Male</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="female" value="female"
							   v-model="checked" @click="filter_checked">
						<label for="female">Female</label>
					</div>
				</div>
				<div class="filter-tab tab "  v-bind:class="{ hidden: Active2 }">
					<a href="#">Disability</a>
					<div class="checkbox">
						<input type="checkbox" id="Visual" value="Visual"
							   v-model="checked"
							   @click="filter_checked"
						>
						<label for="Visual">Visual </label>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="Hearing" value="Hearing"
							   v-model="checked"
							   @click="filter_checked"
						>
						<label for="Hearing">Hearing</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="Physical" value="Physical"
							   v-model="checked"
							   @click="filter_checked"
						>
						<label for="Physical">Physical</label>
					</div>
				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active3 }">
					<a href="#">State of Residence</a>
					<div class="checkbox" v-for="state in states" :key="state">
						<input type="checkbox" v-model="checked" v-bind:value="state.name"  v-bind:id="state.name" @click="filter_checked">
						<label v-bind:for="state.name">{{state.name}}</label>
					</div>
				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active8 }">
					<a href="#">Area of study</a>
					<div class="checkbox" v-for="item in area_of_study" :key="item">
						<input type="checkbox" v-model="checked" v-bind:value="item"  v-bind:id="item" @click="filter_checked">
						<label v-bind:for="item">{{item}}</label>
					</div>
				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active9 }">
					<a href="#">Type of Study</a>
					<div class="checkbox"  >
						<input type="checkbox" v-model="checked" value="part-time"  id="part-time" @click="filter_checked">
						<label for="part-time">part-time</label>
					</div>
					<div class="checkbox"  >
						<input type="checkbox" v-model="checked" value="full-time"  id="full-time" @click="filter_checked">
						<label for="full-time">full-time</label>
					</div>
				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active4 }">
					<a href="#">Amount</a>
					<div class="checkbox">
						<input type="checkbox" v-model="checked" id="Less than $500" value="Less than $500" @click="filter_checked">
						<label for="Less than $500">Less than $500</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" v-model="checked" id="$500 - $1000" value="$500 - $1000" @click="filter_checked">
						<label for="$500 - $1000">$500 - $1000</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" v-model="checked" id="$1000 - $1500" value="$1000 - $1500" @click="filter_checked">
						<label for="$1000 - $1500">$1000 - $1500</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" v-model="checked" id="$1500 - $2500" value="$1500 - $2500" @click="filter_checked">
						<label for="$1500 - $2500">$1500 - $2500</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" v-model="checked" id="$2500 - $5000" value="$2500 - $5000" @click="filter_checked">
						<label for="$2500 - $5000">$2500 - $5000</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" v-model="checked" id="More than $5000" value="More than $5000" @click="filter_checked">
						<label for="More than $5000">More than $5000</label>
					</div>
				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active5 }">
					<a href="#">Application Deadline</a>
					<div class="checkbox" >
						<input type="checkbox" v-model="checked" value="In less than two weeks"  id="In less than two weeks" @click="filter_checked">
						<label for="In less than two weeks">In less than two weeks</label>
					</div>
					<div class="checkbox" >
						<input type="checkbox" v-model="checked" value="In less than one month"  id="In less than one month" @click="filter_checked">
						<label for="In less than one month">In less than one month</label>
					</div>
					<div class="checkbox" >
						<input type="checkbox" v-model="checked" value="In less than three months"  id="In less than three months" @click="filter_checked">
						<label for="In less than three months">In less than three months</label>
					</div>
					<div class="checkbox" >
						<input type="checkbox" v-model="checked" value="More than three months"  id="More than three months" @click="filter_checked">
						<label for="More than three months">More than three months</label>
					</div>

				</div>
				<div class="filter-tab tab " v-bind:class="{ hidden: Active6 }">
					<a href="#">Financial Need</a>
					<div class="checkbox" >
						<input type="checkbox" v-model="checked" value="Financial Need - Yes"  id="Financial Need - Yes" @click="filter_checked">
						<label for="Financial Need - Yes">YES</label>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script>
export default {
	name: "filter_section",
	data: function () {
		return {
			select: '',
			input_placeholder: 'College, major, location or keyword',
			input: '',
			checked: [],
			isActiveCheckbox: true,
			Active1: true,
			Active2: true,
			Active3: true,
			Active4: true,
			Active5: true,
			Active6: true,
			Active7: true,
			Active8: true,
			Active9: true,
			items1: [
				{value: 'african_american', name: 'African American',},
				{value: 'latinx_hispanic', name: 'Latinx/Hispanic',},
				{value: 'native_american', name: 'Native American',},
			],
			amountss: [
				{value: 'gender', name: 'Gender',},
				{value: 'first_generation', name: 'First Generation',},
				{value: 'disability', name: 'Disability',},
				{value: 'financial_need', name: 'Financial Need',},
				{value: 'state', name: 'State',},
				{value: 'zip_code', name: 'Zip Code',},
			],
			states: [
				{value: 'all', name: 'All states',},
				{value: 'AL', name: 'Alabama',},
				{value: 'AK', name: 'Alaska',},
				{value: 'AZ', name: 'Arizona',},
				{value: 'AR', name: 'Arkansas',},
				{value: 'LA', name: 'California',},
				{value: 'CO', name: 'Colorado',},
				{value: 'CT', name: 'Connecticut',},
				{value: 'DE', name: 'Delaware',},
				{value: 'FL', name: 'Florida',},
				{value: 'GA', name: 'Georgia',},
				{value: 'HI', name: 'Hawaii',},
				{value: 'ID', name: 'Idaho',},
				{value: 'IL', name: 'Illinois',},
				{value: 'IN', name: 'Indiana',},
				{value: 'IA', name: 'Iowa',},
				{value: 'KS', name: 'Kansas',},
				{value: 'KY', name: 'Kentucky',},
				{value: 'LA', name: 'Louisiana',},
				{value: 'MA', name: 'Maryland',},
				{value: 'ME', name: 'Maine',},
				{value: 'MD', name: 'Massachusetts',},
				{value: 'MI', name: 'Michigan',},
				{value: 'MN', name: 'Minnesota',},
				{value: 'MS', name: 'Mississippi',},
				{value: 'MO', name: 'Missouri',},
				{value: 'MT', name: 'Montana',},
				{value: 'NE', name: 'Nebraska',},
				{value: 'NV', name: 'Nevada',},
				{value: 'NH', name: 'New Hampshire',},
				{value: 'NJ', name: 'New Jersey',},
				{value: 'NM', name: 'New Mexico',},
				{value: 'NY', name: 'New York',},
				{value: 'NC', name: 'North Carolina',},
				{value: 'ND', name: 'North Dakota',},
				{value: 'OH', name: 'Ohio',},
				{value: 'OK', name: 'Oklahoma',},
				{value: 'OR', name: 'Oregon',},
				{value: 'PA', name: 'Pennsylvania',},
				{value: 'RI', name: 'Rhode Island',},
				{value: 'SC', name: 'South Carolina',},
				{value: 'SD', name: 'South Dakota',},
				{value: 'TN', name: 'Tennessee',},
				{value: 'TX', name: 'Texas',},
				{value: 'UT', name: 'Utah',},
				{value: 'VT', name: 'Vermont',},
				{value: 'VA', name: 'Virginia',},
				{value: 'WA', name: 'Washington',},
				{value: 'WV', name: 'West Virginia',},
				{value: 'WI', name: 'Wisconsin',},
				{value: 'WY', name: 'Wyoming',},

			],
			area_of_study: [
				'Agribusiness', 'Architecture', 'Academic Advising', 'Area/Ethnic Studies', 'African Studies', 'Agriculture', 'Art History', 'Asian Studies', 'American Studies', 'Animal/Veterinary Sciences', 'Anthropology', 'Arts', 'Applied Sciences', 'Accounting', 'Audiology', 'Aviation/Aerospace', 'Archaeology', 'Behavioral Science', 'Biology', 'Business/Consumer Services', 'Campus Activities', 'Canadian Studies',
				'Construction Engineering/Management', 'Child and Family Studies', 'Chemical Engineering', 'Criminal Justice/Criminology', 'Classics', 'Communications', 'Computer Science/Data Processing', 'Culinary Arts', 'Civil Engineering', 'Cosmetology', 'Dental Health/Services', 'Drafting', 'Economics',
				'Education', 'Environmental Health', 'Electrical Engineering/Electronics', 'Entomology', 'Energy and Power Engineering', 'Engineering-Related Technologies', 'Earth Science', 'Engineering/Technology', 'European Studies', 'Environmental Science', 'Fashion Design', 'Flexography', 'Filmmaking/Video', 'Foreign Language', 'Finance', 'Food Service/Hospitality', 'Fire Sciences', 'Food Science/Nutrition', 'Geography', 'Gemology', 'Graphics/Graphic Arts/Printing', 'German Studies', 'Health Administration', 'Hydrology', 'Home Economics', 'Health Information Management/Technology', 'Health and Medical Sciences', 'Horticulture/Floriculture', 'Historic Preservation and Conservation', 'Human Resources', 'History', 'Humanities', 'Hospitality Management', 'Insurance and Actuarial Science', 'Interior Design',
				'International Migration', 'Industrial Design', 'International Studies', 'Journalism', 'Landscape Architecture', 'Literature/English/Writing', 'Library and Information Sciences', 'Law/Legal Services', 'Law Enforcement/Police Administration', 'Meteorology/Atmospheric Science', 'Marine Biology', 'Music', 'Military and Defense Studies', 'Mechanical Engineering', 'Mathematics', 'Marketing', 'Marine/Ocean Engineering', 'Heating, Air-Conditioning, and Refrigeration Mechanics', 'Funeral Services/Mortuary Science', 'Materials Science Engineering, and Metallurgy', 'Museum Studies', 'Neurobiology', 'Natural Sciences', 'Near and Middle East Studies', 'Natural Resources', 'Nuclear Science', 'Nursing', 'Oceanography', 'Oncology', 'Optometry', 'Occupational Safety and Health', 'Osteopathy', 'Performing Arts', 'Public Health', 'Peace and Conflict Studies',
				'Paper and Pulp Engineering', 'Pharmacy', 'Photojournalism/Photography', 'Psychology', 'Political Science', 'Public Policy and Administration', 'Advertising/Public Relations', 'Physical Sciences', 'Philosophy', 'Radiology', 'Real Estate', 'Recreation, Parks, Leisure Studies', 'Religion/Theology', 'Surveying, Surveying Technology, Cartography, or Geographic Information Science', 'Special Education', 'Statistics', 'Social Sciences', 'Sports-Related/Exercise Science', 'Social Services', 'Science, Technology, and Society', 'Therapy/Rehabilitation', 'Transportation', 'Travel/Tourism', 'Trade/Technical Specialties', 'TV/Radio Broadcasting', 'Urban and Regional Planning', "Women's Studies",
			],
			isActive: false,
		}
	},

	methods: {
		ActiveCheckbox1(id) {
			if (id) {
				this.Active1 = id !== 1
				this.Active2 = id !== 2
				this.Active3 = id !== 3
				this.Active4 = id !== 4
				this.Active5 = id !== 5
				this.Active6 = id !== 6
				this.Active7 = id !== 7
				this.Active8 = id !== 8
				this.Active9 = id !== 9
				// this.$nextTick(() => this.$refs.seven.focus())

			}

		},
		open_tab(){
			this.$nextTick(() => this.$refs.sev.focus())
		},
		filter_open(id = '') {

			this.isActive = !this.isActive;
			this.ActiveCheckbox1(-1)
			this.$nextTick(() => this.$refs.focusMe.focus())

		},
		selection(e) {
			this.select = e.target.value
			this.filter_checked()
		},
		create_post() {

			let main_Obj = {
				select: this.select,
				input: this.input,
				checked: this.checked ?? [],
			}
			this.$router.push({  query: main_Obj })
			// this.$router.push({query: main_Obj})
			this.$emit('posts', main_Obj)
		},
		deleteProduct: function (index) {
			index = this.checked.indexOf(index);
			this.checked.splice(index, 1);
			this.filter_checked()
		},
		filter_checked() {
			setTimeout(this.create_post, 200);
		}
	},

	mounted() {


		const div = document.querySelector('#filter_list');
		const div2 = document.querySelector('.filter-by');
		document.addEventListener('click', (e) => {
			const withinBoundaries = e.composedPath().includes(div);
			const withinBoundaries2 = e.composedPath().includes(div2);
			if (!withinBoundaries && !withinBoundaries2) {
				this.isActive = false
				this.isActiveCheckbox = true
			}
		})

		document.addEventListener('keydown', (e) => {
			if (e.keyCode === 27) {
				this.isActive = false
				this.isActiveCheckbox = true

			}
		});


		let url_obj = this.$route.query;

		if (Object.keys(url_obj).length !== 0) {
			this.checked = !url_obj.checked ? [] : Array.isArray(url_obj.checked) ? url_obj.checked : [url_obj.checked];
			this.select = url_obj.select
			this.input = url_obj.input

			this.$emit('posts', url_obj)
		} else {
			this.$emit('posts', url_obj)
		}
	},
}
</script>

<style scoped>

</style>
